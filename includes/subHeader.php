<div id = "subHeader">

    <ul>
        <li> <a href = "/PenaconyExchange/pages/home.php"> Home </a></li>
        <li> <a href = "/PenaconyExchange/pages/library.php"> Library </a></li>
    </ul>

</div>

<style>
    div#subHeader {
        padding: 20px 25px;
        height: auto;
        display: flex;
        justify-content: right;
        align-items: center;
        color: #0f4c81;
        left: 0;
        box-sizing: border-box;
        flex-wrap: wrap; 
    }

    div ul {
        list-style-type: none;
        display: flex;
        flex-wrap: wrap; 
        padding: 0;
        margin: 0;
    }

    div ul li {
        border: 1px solid #ffffff;
        background-color: #ffffff;
        margin: 0 10px;
        white-space: nowrap;
    }

    div a {
        color: #0f4c81;
        font-weight: bold;
        text-decoration: none;
    }

    div a:hover {
        color: #cf9047;
        border-bottom: 2px solid #513141;
        padding-bottom: 2px;
    }

    @media (max-width: 768px) {
        div {
            flex-direction: column;
            align-items: flex-end;
        }

        div ul {
            margin-top: 10px;
            flex-direction: column;
            align-items: flex-end;
        }

        div ul li {
            margin: 5px 0;
        }
    }


</style>