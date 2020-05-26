@include('common/header')
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
            <div class="exception-class">{{$class}}</div>
            <div>{{$msg}}</div>
        </div>
        <div class="exception-local-list">
            @foreach($traces as $k =>$trace)
                <li data-id="{{$trace['id']}}">
                    <div class="exception-list-one">
                        <div>
                            <span class="exception-list-line">{{$trace['id']}}</span>
                            {{$trace['class']}}
                        </div>
                        <div class="exception-list-file">{{$trace['file']}}
                            &nbsp;&nbsp;:&nbsp;
                            {{$trace['line']}}
                        </div>
                    </div>
                    <div class="tag tag-{{$trace['id']}}"
                         style="visibility: {{$k==0?'visible':'hidden'}};"></div>
                </li>
            @endforeach
        </div>
    </div>
    <div class="left-border-right"></div>
    <div class="exception-code-env">
        <div style="margin: 10px;">
            @foreach($exception_codes as $key => $codeItem)
                <div style="display: {{$codeItem['show']}};" id="code_{{$codeItem['id']}}">
                    <div>{{$codeItem['file']}}</div>
                    <div style="background: black;box-sizing: border-box;padding: 15px 0px;font-size: 13px;">
                        @foreach($codeItem['content'] as $k =>$item)
                            <p style="padding: 0px 15px;margin: 0px;{{$item['target']?'background:rgba(255, 100, 100, .3);':($item['next']?'background:rgba(255, 100, 100, .2);':'')}}">
                                <span style="display: inline-block;width: 30px;">{{$item['line']}}</span>{!! $item['content'] !!}
                            </p>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <div style="width: 100%;overflow: auto;">
            @foreach($env as $key =>$vo)
                <h3>{{$key}}{!! empty($vo) ? '<span style="font-size: 12px;margin-left: 10px;color: #aaa;">empty</span>' : '' !!}</h3>
                <table style="font-size: 12px;">
                    @include('exception/arr', ['data'=>$vo])
                </table>
            @endforeach
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
@include('common/footer')