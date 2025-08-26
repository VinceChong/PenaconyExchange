<footer>
    <div id="copy">
        <img src="/PenaconyExchange/assets/image/harmony.png" alt="Harmony icon" width="40" height="40">
        <p>&copy; Harmony Corporation. All rights reserved. All trademarks are property of their respective owners in the Penacony and other countries. Some geospatial data on this website is provided by harmony.org.</p>
    </div>

    <div id="clickable">
        <div class="column">
            <h3>About Us</h3>
            <ul class="footerList">
                <li><a href="">Privacy Policy</a></li>
                <li><a href="">Legal</a></li>
                <li><a href="">About Penacony Exchange</a></li>
            </ul>
        </div>

        <div class="column">
            <h3>Contact Us</h3>
            <p>Email: penaconyExchange@gmail.com</p>
            <p>Phone: 012-345 6789</p>
        </div>

        <div class="column">
            <h3>Social Media</h3>
            <ul class="footerList">
                <li><a href="">Facebook</a></li>
                <li><a href="">X</a></li>
            </ul>
        </div>
    </div>
</footer>

<style>
footer {
    padding: 30px 20px;
    background: linear-gradient(#0f4c81, #939cd4ff);
    color: #fff;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

footer p {
    font-size: 13px;
    line-height: 1.5;
}

#copy {
    display: flex;
    align-items: center;
    gap: 15px;
}

#copy img {
    flex-shrink: 0;
}

#clickable {
    display: flex;
    justify-content: space-evenly;
    flex-wrap: wrap;
    gap: 30px;
}

.column {
    display: flex;
    flex-direction: column;
    min-width: 200px;
}

.column h3 {
    margin-bottom: 10px;
    font-size: 16px;
    border-bottom: 1px solid rgba(255,255,255,0.3);
    padding-bottom: 5px;
}

.footerList {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footerList li {
    margin: 6px 0;
}

footer a {
    color: #ffffff;
    font-weight: 500;
    text-decoration: none;
    transition: 0.3s;
}

footer a:hover {
    color: #cf9047;
    border-bottom: 2px solid #fff;
    padding-bottom: 2px;
}

/* Responsive */
@media (max-width: 768px) {
    #clickable {
        flex-direction: column;
        align-items: center;
    }

    .column {
        align-items: center;
        text-align: center;
    }
}
</style>
