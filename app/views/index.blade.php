<h2>欢迎加入test-wen大家庭！！</h2>
<div id="tree">

</div>
<script src="/static/ynTree/yntree.js"></script>

<script>
    /**下面是数据的初始化设置**/
    var yntree = new YnTree({
        ele: document.getElementById('tree'),  // 挂载节点元素
        hideCheckBox: false,    //是否隐藏复选框
        spread: false,          //是否默认展开树状结构
        spreadChecked: true,    // 是否默认展开选中的树状结构
        checkStrictly: true, //是否父子互相关联，默认true
        // 复选框change事件
        onchange: function (input, yntree) {
            let {value, text, name, checked} = this;
            console.log(value, text, name, checked);
        },
        // 数据集合
        data: [
            {
                checked: false,     // 是否默认选中，默认值为false
                className: "",      // 自定义class名称
                disabled: false,    // 是否禁用，默认值为false
                name: "test",       // 复选框name属性
                text: "测试1",
                sub: [
                    {
                        checked: false,
                        className: "",
                        disabled: false,
                        name: "test",
                        text: "测试11",
                    }, {
                        checked: false,
                        className: "",
                        datas: "",
                        disabled: false,
                        name: "test",
                        text: "测试12",
                    }
                ]
            }, {
                checked: false,
                className: "",
                disabled: false,
                name: "test",
                text: "测试2",
                sub: [
                    {
                        checked: false,
                        className: "",
                        disabled: false,
                        name: "test",
                        text: "测试21",
                    }, {
                        checked: false,
                        className: "",
                        disabled: false,
                        name: "test",
                        text: "测试22",
                    }
                ]
            }
        ],

    });
</script>
