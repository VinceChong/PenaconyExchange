<footer>
    <div id = "copy">
        <img src = "/PenaconyExchange/assets/image/harmony.png" alt = "Harmony icon" width = "40" height= "40">
        <p> &copy; Harmony Corporation. All rights reserved. All trademarks are property of their respective owners in the Penacony and other countries. Some geospatial data on this website is provided by harmony.org. </p>
    </div>

    <div id = "clickable">
        <div class= "column">
            <h3 class = "contact"> About us </h3>
            <ul class = "footerList">
                <li class = "contact"> <a href = ""> Privacy Policy </a> </li>
                <li class = "contact"> <a href = ""> Legal </a> </li>
                <li class = "contact"> <a href = ""> About Penacony Exchange </a> </li>
            </ul>
        </div>

        <div class= "column">
            <h3 class = "contact"> Contact Us </h3>
            <p class = "contact"> Email        : penaconyExchange@gmail.com </p>
            <p class = "contact"> Phone Number : 012-345 6789</p>
        </div>

        <div class= "column">
            <h3 class = "contact"> Social Media </h3>
            <ul class = "footerList">
                <li class = "contact"> <a href = ""> FaceBook </a> </li>
                <li class = "contact"> <a href = ""> X </a> </li>
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
        justify-content: space-evenly;
        color: #ffffff;
    }

    .column {
        display: flex;
        flex-direction: column;
        justify-content: left;
        padding: 5px;
    }

    .contact {
        margin: 1px;
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
        margin: 3px;
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