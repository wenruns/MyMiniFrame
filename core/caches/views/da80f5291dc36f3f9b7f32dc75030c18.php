<?php view_layout("E:/wens/wens/testHtml/core/caches/views/489bd7ec1ca2e0c23c2fc5d93c08744d.php") ?>
<style>
    .exception-body {
        background: gray;
        color: yellow;
        width: 100%;
        height: 98vh;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        box-sizing: border-box;
        overflow: hidden;
    }

    .exception-list {
        width: calc(35% - 1em);
        height: 100%;
        overflow: auto;
    }

    .exception-msg {
        background: #555;
        margin: 10px;
        box-sizing: border-box;
        padding: 3em;
    }

    .exception-class {
        font-size: 1em;
        color: orangered;
        margin-bottom: .2em;
    }

    .exception-local-list {
        margin: 10px;
        box-sizing: border-box;
        padding: 0px;
    }

    li {
        list-style: none;
        background: #666;
        margin: 0px;
        box-sizing: border-box;
        border-top: 1px solid #fff;
        cursor: pointer;
        display: flex;
    }

    .exception-list-one {
        width: calc(100% - 5px);
        padding: 1em;
    }

    .exception-list-line {
        display: inline-block;
        color: #aaa;
        margin-right: .2em;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        width: 1.3em;
        height: 1.3em;
        text-align: center;
        line-height: 1.3em;
        background: #333;
    }

    .exception-list-file {
        color: #999;
        font-size: .8em;
    }

    .tag {
        width: 5px;
        background: red;
    }

    .left-border-right {
        width: 2px;
        background: whitesmoke;
        height: 100%;
    }

    .exception-code-env {
        width: 65%;
        overflow-y: auto;
        overflow-x: hidden;
        height: 100%;
    }
</style>
<div class="exception-body">
    <div class="exception-list">
        <div class="exception-msg">
            <div class="exception-class"><?php echo htmlentities($class); ?></div>
            <div><?php echo htmlentities($msg); ?></div>
        </div>
        <div class="exception-local-list">
            <?php foreach($traces as $k =>$trace) : ?>
                <li data-id="<?php echo htmlentities($trace['id']); ?>">
                    <div class="exception-list-one">
                        <div>
                            <span class="exception-list-line"><?php echo htmlentities($trace['id']); ?></span>
                            <?php echo htmlentities($trace['class']); ?>
                        </div>
                        <div class="exception-list-file"><?php echo htmlentities($trace['file']); ?>
                            &nbsp;&nbsp;:&nbsp;
                            <?php echo htmlentities($trace['line']); ?>
                        </div>
                    </div>
                    <div class="tag tag-<?php echo htmlentities($trace['id']); ?>"
                         style="visibility: <?php echo htmlentities($k==0?'visible':'hidden'); ?>;"></div>
                </li>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="left-border-right"></div>
    <div class="exception-code-env">
        <div style="margin: 10px;">
            <?php foreach($exception_codes as $key => $codeItem) : ?>
                <div style="display: <?php echo htmlentities($codeItem['show']); ?>;" id="code_<?php echo htmlentities($codeItem['id']); ?>">
                    <div><?php echo htmlentities($codeItem['file']); ?></div>
                    <div style="background: black;box-sizing: border-box;padding: 15px 0px;font-size: 13px;">
                        <?php foreach($codeItem['content'] as $k =>$item) : ?>
                            <p style="padding: 0px 15px;margin: 0px;<?php echo htmlentities($item['target']?'background:rgba(255, 100, 100, .3);':($item['next']?'background:rgba(255, 100, 100, .2);':'')); ?>">
                                <span style="display: inline-block;width: 30px;"><?php echo htmlentities($item['line']); ?></span><?php echo html_entity_decode( $item['content'] ); ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div style="width: 100%;overflow: auto;">
            <?php foreach($env as $key =>$vo) : ?>
                <h3><?php echo htmlentities($key); ?><?php echo html_entity_decode( empty($vo) ? '<span style="font-size: 12px;margin-left: 10px;color: #aaa;">empty</span>' : '' ); ?></h3>
                <table style="font-size: 12px;">
                    <?php view_layout("E:/wens/wens/testHtml/core/caches/views/978037c11149e64c17dd3f53eadcb5e2.php", ['data'=>$vo]) ?>
                </table>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<script>
    window.onload = function () {
        document.querySelectorAll('.exception-local-list li').forEach(function (item, key) {
            item.addEventListener('click', function (e) {
                document.querySelector('.exception-code-env').scrollTo(0, 0);
                try {
                    e.path.forEach(function (vo, k) {
                        if (vo.localName == 'li') {
                            var id = vo.attributes['data-id'].value;

                            document.querySelector('#code_' + id).style.display = 'block';
                            var _elem = document.querySelector('#code_' + id), elem = _elem;
                            // console.log(_elem, _elem.nextElementSibling);
                            while (_elem.previousElementSibling) {
                                _elem = _elem.previousElementSibling;
                                _elem.style.display = 'none';
                            }
                            while (elem.nextElementSibling) {
                                elem = elem.nextElementSibling;
                                elem.style.display = 'none';
                            }


                            vo.querySelector('.tag').style.visibility = '';
                            var _vo = vo;

                            while (vo.previousElementSibling) {
                                vo = vo.previousElementSibling;
                                vo.querySelector('.tag').style.visibility = 'hidden';
                            }
                            while (_vo.nextElementSibling) {
                                _vo = _vo.nextElementSibling;
                                _vo.querySelector('.tag').style.visibility = 'hidden';
                            }

                            throw new Error('Job is worked');
                        }

                    });
                } catch (e) {
                }

            });
        });
    };
</script>
<?php view_layout("E:/wens/wens/testHtml/core/caches/views/b5773f00c36ea9e3b3b5116a6ff81768.php") ?>