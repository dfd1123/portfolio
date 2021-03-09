<div class="ft_button">
    <button type="button" anim="ripple">{{$ft_btn_name}}</button>
</div>

<style>
    .ft_button{
        position:fixed;
        bottom:0;
        left:0;
        width:100%;
        z-index:3;
    }

    .ft_button button{
        box-shadow: 0 -1px 19px rgba(36, 36, 36, 0.17);
        background-color: #d5dcea;
        line-height: 2.06;
        letter-spacing: -0.7px;
        color: #ffffff;
        font-size: 1em;
        width: 100%;
        height: 3.4em;
        border-top-right-radius: 26px;
        border-top-left-radius: 26px;
        outline: none;
        border: none;
        padding-bottom: 0.5em;
    }

    .ft_button button.active{
        background-color:#17334a;
    }
</style>