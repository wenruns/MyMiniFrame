<?php foreach($data as $key =>$var) : ?>
    <section class="one-group-data">
        <?php view_layout("E:/wens/wens/testHtml/core/caches/views/2fbb9de24cf4ab039fc90cdfc1b2f296.php",['data'=>$var,'enter'=>true]) ?>
    </section>
<?php endforeach; ?>
<style>
    .one-group-data {
        background: #444;
        color: lawngreen;
        padding: 10px;
        margin-top: 10px;
    }

    * {
        padding: 0px;
        margin: 0px;
        list-style: none;
    }

    .arr-next {
        margin-left: 30px;
    }

    .arrowhead {
        display: inline-block;
        width: 7px;
        height: 7px;
        border-right: 1px solid lawngreen;
        border-top: 1px solid lawngreen;
        -webkit-transform: rotate(45deg);
        -moz-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        -o-transform: rotate(45deg);
        transform: rotate(45deg);
        /*background-color: #fff;*/
        margin: 0px 5px;
    }

    .arrowhead:before {
        content: '=';
        display: inline-block;
        position: absolute;
        top: -6px;
        left: -5px;
        -webkit-transform: rotate(-45deg);
        -moz-transform: rotate(-45deg);
        -ms-transform: rotate(-45deg);
        -o-transform: rotate(-45deg);
        transform: rotate(-45deg);
    }
</style>