<?php
use app\models\ReFundShopDo;
/** @var ReFundShopDo[] $shopList */
?>

<!-- <select name="" id="">
    <?php foreach ($shopList as $shopDo): ?>
        <option value=""><?= $shopDo->name ?></option>
    <?php endforeach; ?>
</select> -->

<!-- 负评动态变化 -->
<style>
    form{
        margin-left:3%;

    }
    .buttonContent{
        float:right;
        margin-right:30px;
    }
    .commited{
        background-color:#23C6C8;
        border-radius:5px;
        box-shadow:none;
        font-size:12px;
        border:0;
        color:#fff;
        width:80px;
        height:28px;
        padding:2px
    }
    #submitProblem {
        background-color:#23C6C8;
        border-radius:5px;
        box-shadow:none;
        border:0;
        color:#fff;
        width:80px;
        height:28px;
        font-size:12px;
        float:right;
        margin-right:30px;
        padding:2px;
    }
    #father,#son{
        overflow-x:scroll;
    }
    .shop{
        margin-right:10px;
    }
    .productid{
        margin-right:10px;
        position:relative;
    }
    .productid input{
        display:inline-block;
    }
    .levelLabel{
        font-size: 12px;
        background-color: #fff;
        border: 1px solid #ccc;
        padding: 6px;
        cursor: pointer;
        opacity: 1;
        text-align: center;
        display: block;
    }
    .scrollTmallPro {
        overflow-x: auto;
        overflow-y: hidden;
    }
</style>
<div class="pageHeader list-pageHeader" id="analisisDetail-pageHeader">
    <div>
        <div class="search-lg-bar">
            <div class="page-header-tr1">
                <div class="shop">
                    店铺：&nbsp;&nbsp;<select name="" id="shopId" class="all-select">
                    <?php foreach ($shopList as $shopDo): ?>
                    <option value=""><?= $shopDo->name ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <div class="productid">
                    产品货号：&nbsp;&nbsp;<input type="text" name="product_number"
                           value="<?= isset($_POST['product_number']) && !empty($_POST['product_number']) ? $_POST['product_number'] : null; ?>"
                           placeholder="请输入"  onkeyup="getOem($(this).val())" id="Number">
                           <div id="productNumber"></div>
                </div>
                <div>
                    评价时间:&nbsp;&nbsp;<input type="text" id="beginTime" readonly="readonly" size='20' class="date"
                                name="beginTime" dateFmt="yyyy-MM-dd "
                                value="<?= isset($_POST['begin_time']) && !empty($_POST['begin_time']) ? $_POST['begin_time'] : null; ?>"/>
                    &nbsp;&nbsp;- &nbsp;&nbsp;<input type="text" size='20' id="endTime" readonly="readonly"  class="date"
                              name="endTime" dateFmt="yyyy-MM-dd "
                              value="<?= isset($_POST['end_time']) && !empty($_POST['end_time']) ? $_POST['end_time'] : NULL; ?>"/>
                </div>
                    <div class="buttonContent">
                        <button type="submit" id="submit" class="commited">统计</button>
                    </div>
            </div>
        </div>
    </div>
</div>
<!-- 定义一个父容器 -->
<div id="father" style='height:400px;margin-bottom:15px;'></div>
<div class="shop">
    产品问题类型:&nbsp;&nbsp;
    <select name="product" id="productId" class="all-select">
        <option>请选择</option>
    </select>
        <button type="submit" id="submitProblem" class="commited">统计</button>
</div>
<div id="son" style='height:400px'></div>
<script type="text/javascript">
// 定义一个全局变量，存放产品货号的数据；
var productData = [];
// 基于准备好的dom，初始化echarts实例
    var fchart = echarts.init(document.getElementById('father'));
    var schart = echarts.init(document.getElementById('son'));
    // 定义图表配置项和数据
    fchart.setOption({
            title:{
                // text:'产品问题类型'
            },
            tooltip:{
                trigger:'axis'
            },
            toolbox:{
                show:true,
            },
            // color:function(d){return "#"+Math.floor(Math.random()*(256*256*256-1)).toString(16);},
            // color:['#DA5EAB','#FA9C7F','#DEAC01','#73E1E1','#43A1D6'],
            //关键加上这句话，legend的颜色和折线的自定义颜色就一致了
            legend:{
                bottom:'0',
                data:[],
            },
           grid:{
               bottom:'24%'
           },
            xAxis:{
                type:'category',
                data:[],
                boundaryGap:false,
            },
            yAxis:{
                type: 'value',
                name:'%',
                //    调整刻度标签与轴线之间的距离
                axisLabel:{
                    margin:37,
                    //是否显示最大 tick 的 label
                    showMaxLabel: false,
                },
                //坐标轴轴线。
                axisLine:{
                    show:true
                },
                //是否显示坐标轴刻度。
                axisTick:{
                    show:false
                },
            },
            series:[{
                type: 'line',
            }]
    });
    schart.setOption({
        title:{
            // text:'产品问题类型'
        },
        tooltip:{
            trigger:'axis'
        },
        toolbox:{
            show:true,
        },
        // color:['#DA5EAB','#FA9C7F','#DEAC01','#73E1E1','#43A1D6'],
        //关键加上这句话，legend的颜色和折线的自定义颜色就一致了
        legend:{
            bottom:'0',
            data:[],
        },
        grid:{
            bottom:'24%'
        },
        xAxis:{
            type:'category',
            data:[],
            boundaryGap:false
        },
        yAxis:{
            type: 'value',
            name:'%',
            //    调整刻度标签与轴线之间的距离
            axisLabel:{
                margin:37,
                //是否显示最大 tick 的 label
                showMaxLabel: false,
            },
            //坐标轴轴线。
            axisLine:{
                show:true
            },
            //是否显示坐标轴刻度。
            axisTick:{
                show:false
            },
        },
        series:[{
            type: 'line',
        }]
    });
    // 关联两个图表
echarts.connect([fchart, schart]);
// 获取input输入框的值
function getOem(e) {
    //也可以用 e==''，但 e.trim().length === 0更规范，判断有无输入
    if (e.trim().length === 0) {
        $("#productNumbernput").css("display", "none");
    } else {
        var strLabel = "";
        for(var i = 0;i<productData.length;i++) {
            productNum = productData[i].productNumber;
            if(productNum.indexOf(e) >= 1) {
                strLabel += "<span class='levelLabel' onclick='secondVe($(this))'>" + productData[i].productNumber + "</span>";
            }
        }
        if (strLabel.length === 0) {
            strLabel = "<p class='levelLabel'>搜索不到关键字</p>";
        }
        if (strLabel) {
            $('#productNumber').html(strLabel);
            $('#productNumber').css({'height':'40px',
                'width':'120px','position':'absolute','left':'70px','top':'25px'});
        }
    }
};
// 将选中的值赋给input框，并隐藏ID为productNumber的div里的内容
function secondVe(e) {
    $("#Number").val(e.text());
    $("#productNumber").hide();
};
// ajax请求获取图一数据
$('#submit').click(function(){
    if (!($('#shopId').val())) {
            alertMsg.warn("您提交的店铺名为空，请检查后重新提交！");
    }
    else if(!($('#beginTime').val())){
        lertMsg.warn("您提交的起始日期为空，请检查后重新提交！");
    }
    else if(!($('#endTime').val())){
        lertMsg.warn("您提交的结束日期为空，请检查后重新提交！");
    }
    else if(!($('#productNumber').val())){
        lertMsg.warn("您提交的产品货号为空，请检查后重新提交！");
    }
    else{
        $.ajax({
            type: "GET",
            //访问地址
            url: "<?= $this->createUrl('/ProductAnalysisDetail/NegativeDynamicChange') ?>",
            // 接口要求参数，每个接口都有每个接口的参数要求
            data: {
                "shopId": $("#shopId").val(),
                "beginTime": $("#beginTime").val(),
                "endTime": $("#endTime").val(),
                "productNumber": $("#productNumber").val()
            },
            dataType: "json",
            success: function (res) {
                var datalist=res.data.data;
                var timeRangeList=res.data.timeRangeList;
                // 定义一个数组，存放series所需要name，data的数据
                var dataArr = [];
                // 定义三个数组分别存放problemTypeId，problemType，dataPath的数据
                var problemTypeIdList = [];
                var problemTypeList = [];
                var dataPathList = [];
                var timeList=[];
                console.log(timeRangeList);
                // 将后端传过来的数据赋值给定义的三个空数组
                for(var i=0;i<datalist.length;i++){
                    problemTypeIdList.push(datalist[i].problemTypeId);
                    problemTypeList.push(datalist[i].problemType);
                    dataPathList.push(datalist[i].dataPath)
                };
                // problemTypeList，dataPathList遍历赋给dataArr
                for(var i=0;i<datalist.length;i++){
                    dataArr.push(item(dataPathList, problemTypeList, i));
                }
                console.log(dataArr);
                // 将获取的图表数据渲染到图表上
                    fchart.setOption({
                        
                        legend:{
                            data:problemTypeList,
                        },
                        xAxis:{
                            data:timeRangeList,
                        },
                        series: dataArr
                    }); 
                    // 自动获取数据到selest框
                    $("select[name=product]").empty();
                    var optionString = `<option value='aaa'>`+ 'aaa' +`</option>`;
                    var optionlist = datalist;
                    for(var i = 0;i<optionlist.length;i++){
                        $("select[name=product]").append('<option value='+ optionlist[i].problemTypeId +'>'+ optionlist[i].problemType+'</option>');
                    }
            },
        })
    }
});
// 将dataPathList，problemTypeList封装到item中
function item(dataPathList, problemTypeList, a){
    var obj = {};
    obj.name = problemTypeList[a];
    obj.type = 'line';
    obj.data = dataPathList[a];
    return obj;
};
// ajax请求获取图二数据
$('#submitProblem').click(function(){
    $.ajax({
        type:"GET",
        url:"<?= $this->createUrl('/ProductAnalysisDetail/NegativeDynamicChange') ?>",
        data: {
            "shopId": $("#shopId").val(),
            "beginTime": $("#beginTime").val(),
            "endTime": $("#endTime").val(),
            "productNumber": $("#productNumber").val(),
            "productId":$("#productId").val(),
            },
        dataType:"json",
        success:function (res) {
            var Problemlist = res.data.data;
            var Timelisst = res.data.timeRangeList;
            var dataPath = [];
            var problemType = [];
            var dataArr = [];
            for(var i=0;i<Problemlist.length;i++){
                dataPath.push(Problemlist[i].dataPath);
                problemType.push(Problemlist[i].problemType);
            };
            for(var i=0;i<problemType.length;i++){
                dataArr.push(Dataitem(dataPath, problemType, i));
            };
            schart.setOption({
                legend:{
                    data:problemType,
                },
                xAxis:{
                data:Timelisst,
                },
                series: dataArr
            }); 
        }
    })
    function Dataitem(dataPath,problemType,a){
        var obj = {};
        obj.name = problemType[a];
        obj.type = 'line';
        obj.data = dataPath[a];
        return obj;
    }
})
// 初始化就请求获取产品货号的数据
$(function() {
    $.ajax({
        type:'POST',
        url:"<?= $this->createUrl('/ProductSupplyAgain/GetProductName') ?>",
        data:{
            goods_number: '',
            name: '',
            type: ''
        },
        dataType:'json',
        success:function(result) {
            productData = result;
        },
        error:function(err) {
            console.log(err);
        }
    });
});
</script>
