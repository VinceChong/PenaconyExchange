<header>
    <h2> PENACONY EXCHANGE </h2>

    <ul>
        <li> <a href = "/PenaconyExchange/pages/home.php"> Home </a></li>
        <li> <a href = "/PenaconyExchange/pages/library.php"> Library </a></li>
        <li> <a href = "/PenaconyExchange/pages/community.php"> Community </a></li>
        <li> <a href = "/PenaconyExchange/pages/wishlist.php"> Wishlist </a></li>
        <li> <a href = "/PenaconyExchange/pages/cart.php"> Cart </a></li>
        <li> <a href = "/PenaconyExchange/pages/profile.php"> Profile </a></li>
        
    </ul>

</header>

<style>
    header {
        width: 100%;
        padding: 20px 25px;
        background: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #0f4c81;
        position: fixed;
        top: 0;
        left: 0;
        box-sizing: border-box;
        flex-wrap: wrap; 
    }

    header h2 {
        margin: 0;
        flex-shrink: 0;
    }

    header ul {
        list-style-type: none;
        display: flex;
        flex-wrap: wrap; 
        padding: 0;
        margin: 0;
    }

    header ul li {
        margin: 0 10px;
        white-space: nowrap;
    }

    header a {
        color: #0f4c81;
        font-weight: bold;
        text-decoration: none;
    }

    header a:hover {
        color: #cf9047;
        border-bottom: 2px solid #513141;
        padding-bottom: 2px;
    }

    @media (max-width: 768px) {
        header {
            flex-direction: column;
            align-items: flex-start;
        }

        header ul {
            margin-top: 10px;
            flex-direction: column;
            align-items: flex-start;
        }

        header ul li {
            margin: 5px 0;
        }
    }


</style>