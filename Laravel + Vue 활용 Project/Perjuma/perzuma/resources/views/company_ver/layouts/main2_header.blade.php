<header id="main_hd">
    <div class="center_hd">
        <img src="{{asset('/images/logo_header.svg')}}" alt="Logo" />
    </div>
</header>


<style>
#main_hd{
    background:#fff;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 5;
}

.left_hd{
    position: absolute;
    top: 2.6em;
    left: 1.3em;
    z-index: 3;
}

.left_hd img{
    width: 1.7em;
}

.center_hd{
    text-align: center;
    padding: 1.2em 0;
}

.center_hd img{
    display:block;
    margin: 0 auto;
    width:5.55em;
}

.right_hd{
    position: absolute;
    top: 1.05em;
    right: 1.3em;
    z-index: 3;
}

.right_hd img{
    width: 1.3em;
}

.right_hd span:last-child{
    margin-bottom:0;
}

/*
    #main_hd{
        background: rgba(0,123,210,0.8);
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 5;
    }

    .left_hd{
        position: absolute;
        top: 2.6em;
        left: 1.3em;
        z-index: 3;
    }

    .left_hd img{
        width: 1.7em;
    }

    .center_hd{
        text-align: center;
        padding: 1.8em 0;
        padding-top: 2.3em;
    }

    .center_hd img{
        display:block;
        margin: 0 auto;
    }

    .center_hd img:first-child{
        max-width: 7em;
        margin-bottom: 0.7em;
    }

    .right_hd{
        position: absolute;
        top: 2.6em;
        right: 1.3em;
        z-index: 3;
    }

    .right_hd span{
        display: block;
        width: 1.5em;
        height: 1px;
        margin-bottom: 0.48em;
        background-color: #ffffff;
    }

    .right_hd span:last-child{
        margin-bottom:0;
    }
*/
</style>