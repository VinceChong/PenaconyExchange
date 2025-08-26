<?php
// pages: PenaconyExchange/db/backend/publisher/insertGame.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// ===== DEV ERROR OUTPUT (remove in production) =====
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Safer include using absolute path from this file
require_once __DIR__ . "/../db.php"; // -> PenaconyExchange/db/backend/db.php

// ---------- Helpers ----------
function bad_request(string $msg) : void {
  http_response_code(422);
  echo htmlspecialchars($msg, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
  exit;
}
function server_error(string $msg = "Failed to insert game.") : void {
  http_response_code(500);
  echo htmlspecialchars($msg, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
  exit;
}
function reArrayFiles(array $filePost): array {
  $files = [];
  $count = isset($filePost['name']) ? count($filePost['name']) : 0;
  $keys  = array_keys($filePost);
  for ($i=0; $i<$count; $i++) {
    $files[$i] = [];
    foreach ($keys as $k) { $files[$i][$k] = $filePost[$k][$i] ?? null; }
  }
  return $files;
}
function ensureDir(string $dir): void {
  if (!is_dir($dir)) {
    if (!mkdir($dir, 0775, true)) {
      throw new RuntimeException("mkdir failed: $dir");
    }
  }
}
function ext_from_name(string $name): string {
  $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
  return preg_replace('/[^a-z0-9]+/', '', $ext);
}
function is_allowed_image_ext(string $ext): bool {
  return in_array($ext, ['jpg','jpeg','png','gif','webp'], true);
}
function is_allowed_video_ext(string $ext): bool {
  return in_array($ext, ['mp4','webm','ogg'], true);
}
function move_uploaded_file_safe(array $f, string $dstAbs): bool {
  return isset($f['tmp_name']) && is_uploaded_file($f['tmp_name']) && move_uploaded_file($f['tmp_name'], $dstAbs);
}

// ---------- Read input ----------
$title       = trim($_POST['title'] ?? '');
$desc        = trim($_POST['desc'] ?? '');
$detailDesc  = trim($_POST['detailDesc'] ?? '');
$price       = (float)($_POST['price'] ?? 0);
$releaseDate = $_POST['releaseDate'] ?? '';
$categories  = $_POST['categories'] ?? []; // array of categoryId

// Publisher from session
$publisherId = 0;
if (isset($_SESSION['publisher']['publisherId'])) {
  $publisherId = (int)$_SESSION['publisher']['publisherId'];
} elseif (isset($_SESSION['publisher']['id'])) {
  $publisherId = (int)$_SESSION['publisher']['id'];
}
if ($publisherId <= 0) bad_request("Invalid publisher session.");

if ($title === '' || $desc === '' || $releaseDate === '') {
  bad_request("Title, Description, and Release Date are required.");
}
if (empty($categories) || !is_array($categories)) {
  bad_request("Please select at least one category.");
}
if (empty($_FILES['mainPic']['tmp_name'])) {
  bad_request("Main picture is required.");
}

// System Requirements (Minimum & Recommended)
$min = [
  'os'        => trim($_POST['minOs'] ?? ''),
  'processor' => trim($_POST['minProcessor'] ?? ''),
  'memory'    => trim($_POST['minMemory'] ?? ''),
  'graphic'   => trim($_POST['minGraphic'] ?? ''),
  'directX'   => trim($_POST['minDirectX'] ?? 'N/A'),
  'storage'   => trim($_POST['minStorage'] ?? ''),
  'soundCard' => trim($_POST['minSoundCard'] ?? 'N/A'),
];
$rec = [
  'os'        => trim($_POST['recOs'] ?? ''),
  'processor' => trim($_POST['recProcessor'] ?? ''),
  'memory'    => trim($_POST['recMemory'] ?? ''),
  'graphic'   => trim($_POST['recGraphic'] ?? ''),
  'directX'   => trim($_POST['recDirectX'] ?? 'N/A'),
  'storage'   => trim($_POST['recStorage'] ?? ''),
  'soundCard' => trim($_POST['recSoundCard'] ?? 'N/A'),
];
// enforce minimum core fields
foreach (['os','processor','memory','graphic','storage'] as $k) {
  if ($min[$k] === '') bad_request("Minimum system requirement '{$k}' is required.");
}

// ---------- Start transaction ----------
mysqli_begin_transaction($connect);

try {
  // 1) Insert Game (temporary mainPicture='N/A' until we save the file)
  $stmt = mysqli_prepare(
    $connect,
    "INSERT INTO Game (gameTitle, gameDesc, mainPicture, price, releaseDate, publisherId)
     VALUES (?, ?, 'N/A', ?, ?, ?)"
  );
  mysqli_stmt_bind_param($stmt, "ssdsi", $title, $desc, $price, $releaseDate, $publisherId);
  if (!mysqli_stmt_execute($stmt)) {
    throw new RuntimeException("Insert Game: " . mysqli_error($connect));
  }
  $gameId = mysqli_insert_id($connect);
  mysqli_stmt_close($stmt);

  // 2) Prepare upload directories (absolute FS) and relative URLs for DB
  // project root = PenaconyExchange/
  $projectRoot = realpath(__DIR__ . "/../../..");
  if ($projectRoot === false) throw new RuntimeException("Cannot resolve project root.");

  $baseRel  = "/uploads/games/" . $gameId; // URL path (store this in DB)
  $baseAbs  = $projectRoot . $baseRel;     // absolute disk path

  $dirMain   = $baseAbs;
  $dirImages = $baseAbs . "/images";
  $dirVideos = $baseAbs . "/videos";
  $dirThumbs = $baseAbs . "/video_thumbs";
  ensureDir($dirMain); ensureDir($dirImages); ensureDir($dirVideos); ensureDir($dirThumbs);

  // 3) Save main picture file and update Game.mainPicture
  $mainExt = ext_from_name($_FILES['mainPic']['name'] ?? '');
  if (!is_allowed_image_ext($mainExt)) throw new RuntimeException("Unsupported main picture type.");
  $mainRel = $baseRel . "/main." . $mainExt;
  $mainAbs = $dirMain . "/main." . $mainExt;
  if (!move_uploaded_file_safe($_FILES['mainPic'], $mainAbs)) {
    throw new RuntimeException("Failed to save main picture.");
  }
  $stmt = mysqli_prepare($connect, "UPDATE Game SET mainPicture=? WHERE gameId=?");
  mysqli_stmt_bind_param($stmt, "si", $mainRel, $gameId);
  if (!mysqli_stmt_execute($stmt)) throw new RuntimeException("Update main picture: " . mysqli_error($connect));
  mysqli_stmt_close($stmt);

  // 4) Link categories
  $gc = mysqli_prepare($connect, "INSERT INTO GameCategory (gameId, categoryId) VALUES (?, ?)");
  foreach ($categories as $cid) {
    $cid = (int)$cid;
    mysqli_stmt_bind_param($gc, "ii", $gameId, $cid);
    if (!mysqli_stmt_execute($gc)) throw new RuntimeException("Insert GameCategory: " . mysqli_error($connect));
  }
  mysqli_stmt_close($gc);

  // 5) Previews: images[]
  $imagesRel = [];
  if (!empty($_FILES['images']['name'][0])) {
    $images = reArrayFiles($_FILES['images']);
    $stmtImg = mysqli_prepare(
      $connect,
      "INSERT INTO GamePreview (gameId, type, title, url, thumbnail) VALUES (?, 'image', ?, ?, ?)"
    );
    foreach ($images as $i => $f) {
      if (($f['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) continue;
      $ext = ext_from_name($f['name'] ?? '');
      if (!is_allowed_image_ext($ext)) continue;

      $fname = "img_" . ($i+1) . "." . $ext;
      $abs   = $dirImages . "/" . $fname;
      $rel   = $baseRel   . "/images/" . $fname;

      if (move_uploaded_file_safe($f, $abs)) {
        $titleImg = pathinfo($f['name'], PATHINFO_FILENAME);
        $thumbRel = $rel; // images can use themselves as thumbnails
        mysqli_stmt_bind_param($stmtImg, "isss", $gameId, $titleImg, $rel, $thumbRel);
        if (!mysqli_stmt_execute($stmtImg)) throw new RuntimeException("Insert GamePreview(image): " . mysqli_error($connect));
        $imagesRel[] = $rel;
      }
    }
    mysqli_stmt_close($stmtImg);
  }

  // 6) Previews: videoThumbs[] (collect by index) + videos[]
  $thumbRelByIndex = [];
  if (!empty($_FILES['videoThumbs']['name'][0])) {
    $thumbFiles = reArrayFiles($_FILES['videoThumbs']);
    foreach ($thumbFiles as $tidx => $tf) {
      if (($tf['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) { $thumbRelByIndex[$tidx] = null; continue; }
      $ext = ext_from_name($tf['name'] ?? '');
      if (!is_allowed_image_ext($ext)) { $thumbRelByIndex[$tidx] = null; continue; }
      $fname = "thumb_" . ($tidx+1) . "." . $ext;
      $abs   = $dirThumbs . "/" . $fname;
      $rel   = $baseRel   . "/video_thumbs/" . $fname;
      $thumbRelByIndex[$tidx] = move_uploaded_file_safe($tf, $abs) ? $rel : null;
    }
  }

  $videosRel = [];
  if (!empty($_FILES['videos']['name'][0])) {
    $videos = reArrayFiles($_FILES['videos']);
    $stmtVid = mysqli_prepare(
      $connect,
      "INSERT INTO GamePreview (gameId, type, title, url, thumbnail) VALUES (?, 'video', ?, ?, ?)"
    );
    foreach ($videos as $i => $f) {
      if (($f['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) continue;
      $ext = ext_from_name($f['name'] ?? '');
      if (!is_allowed_video_ext($ext)) continue;

      $fname = "vid_" . ($i+1) . "." . $ext;
      $abs   = $dirVideos . "/" . $fname;
      $rel   = $baseRel   . "/videos/" . $fname;

      if (move_uploaded_file_safe($f, $abs)) {
        $titleVid = pathinfo($f['name'], PATHINFO_FILENAME);
        $thumbRel = $thumbRelByIndex[$i] ?? 'N/A';
        mysqli_stmt_bind_param($stmtVid, "isss", $gameId, $titleVid, $rel, $thumbRel);
        if (!mysqli_stmt_execute($stmtVid)) throw new RuntimeException("Insert GamePreview(video): " . mysqli_error($connect));
        $videosRel[] = $rel;
      }
    }
    mysqli_stmt_close($stmtVid);
  }

  // 7) AboutGame (NOT NULL columns: detailedDesc, videoUrl, imageUrl).
  // 7) Insert SystemRequirement rows (minimum + recommended)
$stmt = mysqli_prepare(
    $connect,
    "INSERT INTO SystemRequirement
     (gameId, type, os, processor, memory, graphic, directX, storage, soundCard)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

// ----- Minimum (use only variables in bind_param) -----
$typeMin       = 'minimum';
$min_os        = $min['os'];
$min_processor = $min['processor'];
$min_memory    = $min['memory'];
$min_graphic   = $min['graphic'];
$min_directX   = ($min['directX'] === '' ? 'N/A' : $min['directX']);
$min_storage   = $min['storage'];
$min_soundCard = ($min['soundCard'] === '' ? 'N/A' : $min['soundCard']);

mysqli_stmt_bind_param(
    $stmt, "issssssss",
    $gameId, $typeMin,
    $min_os, $min_processor, $min_memory, $min_graphic,
    $min_directX, $min_storage, $min_soundCard
);
if (!mysqli_stmt_execute($stmt)) {
    throw new RuntimeException("Failed to insert SystemRequirement(min): " . mysqli_error($connect));
}

// ----- Recommended (fallback to min if empty; still variables) -----
$typeRec       = 'recommended';
$rec_os        = ($rec['os']        === '' ? $min_os        : $rec['os']);
$rec_processor = ($rec['processor'] === '' ? $min_processor : $rec['processor']);
$rec_memory    = ($rec['memory']    === '' ? $min_memory    : $rec['memory']);
$rec_graphic   = ($rec['graphic']   === '' ? $min_graphic   : $rec['graphic']);
$rec_directX   = ($rec['directX']   === '' ? 'N/A'          : $rec['directX']);
$rec_storage   = ($rec['storage']   === '' ? $min_storage   : $rec['storage']);
$rec_soundCard = ($rec['soundCard'] === '' ? 'N/A'          : $rec['soundCard']);

mysqli_stmt_bind_param(
    $stmt, "issssssss",
    $gameId, $typeRec,
    $rec_os, $rec_processor, $rec_memory, $rec_graphic,
    $rec_directX, $rec_storage, $rec_soundCard
);
if (!mysqli_stmt_execute($stmt)) {
    throw new RuntimeException("Failed to insert SystemRequirement(rec): " . mysqli_error($connect));
}
mysqli_stmt_close($stmt);


  // 9) Commit + redirect
  mysqli_commit($connect);
  header("Location: /PenaconyExchange/pages/publisher/gamesList.php?added=1");
  exit;

} catch (Throwable $e) {
  mysqli_rollback($connect);
  // In dev, show the exact reason; in prod, change to server_error();
  server_error("Failed to insert game: " . $e->getMessage());
}
