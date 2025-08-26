// /PenaconyExchange/scripts/publisher/addGame.js

(function () {
  const $ = (sel) => document.querySelector(sel);

  // Elements
  const mainPicInput = $("#mainPic");
  const mainPicPreview = $("#mainPicPreview");

  const imagesInput = $("#images");
  const imagesPreview = $("#imagesPreview");

  const videosInput = $("#videos");
  const videosPreview = $("#videosPreview");

  const videoThumbsInput = $("#videoThumbs");
  const videoThumbsPreview = $("#videoThumbsPreview");

  const categoriesSelect = $("#categories");
  const selectedCategoriesWrap = $("#selectedCategories");

  // Helpers
  const fmtSize = (bytes) => {
    if (!bytes && bytes !== 0) return "";
    const units = ["B", "KB", "MB", "GB"];
    let i = 0, v = bytes;
    while (v >= 1024 && i < units.length - 1) { v /= 1024; i++; }
    return `${v.toFixed(1)} ${units[i]}`;
  };

  const clearEl = (el) => { while (el && el.firstChild) el.removeChild(el.firstChild); };

  const previewImages = (fileList, container) => {
    clearEl(container);
    Array.from(fileList || []).forEach((file) => {
      if (!file.type.startsWith("image/")) return;
      const url = URL.createObjectURL(file);
      const wrap = document.createElement("div");
      wrap.className = "thumb";

      const img = document.createElement("img");
      img.src = url;
      img.alt = file.name;

      const meta = document.createElement("div");
      meta.className = "meta";
      meta.textContent = `${file.name} • ${fmtSize(file.size)}`;

      wrap.appendChild(img);
      wrap.appendChild(meta);
      container.appendChild(wrap);
    });
  };

  const previewVideos = (fileList, container) => {
    clearEl(container);
    Array.from(fileList || []).forEach((file) => {
      if (!file.type.startsWith("video/")) return;
      const url = URL.createObjectURL(file);

      const wrap = document.createElement("div");
      wrap.className = "thumb";

      const video = document.createElement("video");
      video.src = url;
      video.controls = true;
      video.preload = "metadata";
      video.style.width = "100%";
      video.style.maxHeight = "180px";
      video.style.borderRadius = "6px";

      const meta = document.createElement("div");
      meta.className = "meta";
      meta.textContent = `${file.name} • ${fmtSize(file.size)}`;

      wrap.appendChild(video);
      wrap.appendChild(meta);
      container.appendChild(wrap);
    });
  };

  const updateCategoryChips = () => {
    clearEl(selectedCategoriesWrap);
    Array.from(categoriesSelect.selectedOptions).forEach((opt) => {
      const chip = document.createElement("span");
      chip.className = "chip";
      chip.textContent = opt.textContent;
      selectedCategoriesWrap.appendChild(chip);
    });
  };

  // Wire up events
  if (mainPicInput && mainPicPreview) {
    mainPicInput.addEventListener("change", () => {
      const f = mainPicInput.files && mainPicInput.files[0];
      if (f && f.type.startsWith("image/")) {
        mainPicPreview.src = URL.createObjectURL(f);
        mainPicPreview.style.display = "block";
        mainPicPreview.alt = f.name;
      } else {
        mainPicPreview.style.display = "none";
        mainPicPreview.removeAttribute("src");
      }
    });
  }

  if (imagesInput && imagesPreview) {
    imagesInput.addEventListener("change", () => previewImages(imagesInput.files, imagesPreview));
  }

  if (videosInput && videosPreview) {
    videosInput.addEventListener("change", () => previewVideos(videosInput.files, videosPreview));
  }

  if (videoThumbsInput && videoThumbsPreview) {
    videoThumbsInput.addEventListener("change", () => previewImages(videoThumbsInput.files, videoThumbsPreview));
  }

  if (categoriesSelect && selectedCategoriesWrap) {
    categoriesSelect.addEventListener("change", updateCategoryChips);
    // initialize (in case of browser restore)
    updateCategoryChips();
  }
})();
(function () {
  const $ = (sel) => document.querySelector(sel);

  // --- existing preview code you already have above ---

  // ===== Category buttons logic =====
  const grid = $("#categoriesBtnGrid");
  const chipsWrap = $("#selectedCategories");
  const hiddenWrap = $("#categoriesHidden");
  const form = $("#addGameForm");

  const clearEl = (el) => { while (el && el.firstChild) el.removeChild(el.firstChild); };

  const getSelectedIds = () =>
    Array.from(grid.querySelectorAll(".cat-btn.active")).map(b => b.dataset.id);

  const renderHiddenInputs = () => {
    clearEl(hiddenWrap);
    getSelectedIds().forEach(id => {
      const input = document.createElement("input");
      input.type = "hidden";
      input.name = "categories[]";
      input.value = id;
      hiddenWrap.appendChild(input);
    });
  };

  const renderChips = () => {
    clearEl(chipsWrap);
    grid.querySelectorAll(".cat-btn.active").forEach(btn => {
      const chip = document.createElement("span");
      chip.className = "chip removable";
      chip.dataset.id = btn.dataset.id;
      chip.textContent = btn.textContent;
      chipsWrap.appendChild(chip);
    });
  };

  const toggleById = (id, toActive) => {
    const btn = grid.querySelector(`.cat-btn[data-id="${id}"]`);
    if (!btn) return;
    if (toActive === undefined) btn.classList.toggle("active");
    else btn.classList.toggle("active", !!toActive);
    btn.setAttribute("aria-pressed", btn.classList.contains("active") ? "true" : "false");
  };

  // Button clicks
  if (grid) {
    grid.addEventListener("click", (e) => {
      const btn = e.target.closest(".cat-btn");
      if (!btn) return;
      btn.classList.toggle("active");
      btn.setAttribute("aria-pressed", btn.classList.contains("active") ? "true" : "false");
      renderHiddenInputs();
      renderChips();
    });
  }

  // Chip clicks (remove)
  if (chipsWrap) {
    chipsWrap.addEventListener("click", (e) => {
      const chip = e.target.closest(".chip.removable");
      if (!chip) return;
      const id = chip.dataset.id;
      toggleById(id, false);
      renderHiddenInputs();
      renderChips();
    });
  }

  // Require at least one category
  if (form) {
    form.addEventListener("submit", (e) => {
      if (getSelectedIds().length === 0) {
        e.preventDefault();
        alert("Please select at least one category.");
      }
    });
  }

})();
