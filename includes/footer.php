<footer>
    <div id = "copy">
        <img src = "/PenaconyExchange/assets/image/harmony.png" alt = "Harmony icon" width = "40" height= "40">
        <p> &copy; Harmony Corporation. All rights reserved. All trademarks are property of their respective owners in the Penacony and other countries. Some geospatial data on this website is provided by harmony.org. </p>
    </div>

    <div id = "clickable">
        <div class= "column">
            <ul class = "footerList">
                <li> <a href = ""> Privacy Policy </li>
                <li> <a href = ""> Legal </li>
                <li> <a href = ""> About Penacony Exchange </li>
            </ul>
        </div>

        <div class= "column">
            <ul class = "footerList">
                <li> <a href = ""> FaceBook </li>
                <li> <a href = ""> X </li>
            </ul>
        </div>
    </div>

</footer>

<style>
    footer {
        padding: 20px;
        background: linear-gradient( #0f4c81, #939cd4ff);
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        bottom: 0;
    }

    footer p {
        color: #ffffffff;
        font-size: 12px;
    }
    
    footer div#copy {
        display: flex;
        flex-direction: row;
    }

    footer div#clickable {
        display: flex;
        flex-direction: row;
    }

    .column {
        display: flex;
        flex-direction: column;
        justify-content: left;
    }

    ul#footerList {
        display: flex;
        flex-direction: column;
        list-style-type: none;
        display: flex;
        padding: 0;
        margin: 0 50px;
    }

    ul#footerList li {
        margin: 0 10px;
        white-space: nowrap;
    }

    a {
        color: #ffffffff;
        font-weight: bold;
        text-decoration: none;
    }

    a:hover {
        color: #cf9047;
        border-bottom: 2px solid #ffffffff;
        padding-bottom: 2px;
    }

</style>