var myChart = null;
function cImg(dom,fg,d){
    var dom = document.getElementById(dom);
    myChart= echarts.init(dom,fg);
    
    var dd = [];
    $.each(d.data.d,function(i,v){
        v = eval('('+v+')');
        dd.push(v);
    });
    pie_op2 = {
        title:{text: d.ms},
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        legend: {       
            x: 'right',
            orient: 'vertical',
            data: d.data.s
        },
        series: [
            {
                name:d.t,
                type:'pie',
                radius: ['30%', '60%'],
                avoidLabelOverlap: false,
                label: {
                    normal: {
                        position: 'inner', 
                    },
                    emphasis: {
                        show: true,
                        textStyle: {
                            fontSize: '30',
                            fontWeight: 'bold'
                        }
                    }
                },
                labelLine: {
                    normal: {
                        show: false
                    }
                },
                data:dd
            },{
                  type:'pie',
                  radius: ['28%', '29%'],
                  label: { normal: { show: false, }}, 
                  labelLine: {show: false}, 
                  data:[ {value:0, name:''}]
                },
                {
                  type:'pie',
                  radius: ['61%', '62%'],
                  label: { normal: { show: false, }}, 
                  labelLine: {show: false}, 
                  data:[ {value:0, name:''}]
                }
        ]
    };
   var app = {}; 
   if (pie_op2 && typeof pie_op2 === "object") 
    { 
        myChart.setOption(pie_op2, true);
        //myChart.resize();

    }
}

function chg(obj){
    if($(obj).prop("data-tag")==1){
        myChart.dispose();
        cImg('index-pie','',data2);
        $(obj).prop("data-tag",'2');
        console.log($(obj).prop("data-tag"));
    } else{
        myChart.dispose();
        cImg('index-pie','shine',data);
        $(obj).prop("data-tag",'1');
        console.log($(obj).prop("data-tag"));
    }
    
}

function cImgguojia(){
    var dom = document.getElementById('index-guojia-bar');
    myChartbar = echarts.init(dom,'shine');
    if (ops && typeof ops === "object") 
    { 
        myChartbar.setOption(ops, true);
    }

}

function cImgyears(){
    var dom = document.getElementById('index-years-bar');
    myChartbar2 = echarts.init(dom);
    if (ops2 && typeof ops2 === "object") 
    { 
        myChartbar2.setOption(ops2, true);
    }

}
myChartbar = null;
myChartbar2 = null;
var dataAxis = ['韩国','\n日本','新加坡','\n印度尼西亚','马来西亚','\n泰国','中国','\n越南','缅甸','\n澳大利亚','美国','\n英国','俄罗斯','\n加拿大','法国','\n菲律宾','蒙古国','\n德国','香港地区','\n瑞士'];
var databar = [4171,3211,843,739,422,374,232,227,202,123,118,108,82,80,67,64,59,43,37,20];
var yMax = 4200;
var dataShadow = [];
for (var i = 0; i < databar.length; i++) {
    dataShadow.push(yMax);
}
ops = {
    title: {
        text: '按国籍汇总篇章信息统计图'
    },
    tooltip : {
        trigger: 'axis',
        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
        }
    },
    toolbox: {
            show : true,
            feature : {
                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        legend: {
        data:['篇章总数']
    },
    xAxis: {
        data: dataAxis,
        axisLabel: {
            interval:0,
            textStyle: {
                color: '#003366'
            }
        },
        axisTick: {
            show: false
        },
        axisLine: {
            show: false
        },
        splitLine: {show: false}
    },
    yAxis: {
        axisLine: {
            show: false
        },
        axisTick: {
            show: false
        },
        axisLabel: {
            textStyle: {
                color: '#999'
            }
        }
    },
   
    series: [
        { // For shadow
            type: 'bar',
            itemStyle: {
                normal: {color: '#FF6666'}
            },
            barGap:'-100%',
            data: dataShadow,
            animation: false
        },
        {
            type: 'bar',
            itemStyle: {
                normal: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#83bff6'},
                            {offset: 0.5, color: '#188df0'},
                            {offset: 1, color: '#188df0'}
                        ]
                    )
                },
                emphasis: {
                    color: new echarts.graphic.LinearGradient(
                        0, 0, 0, 1,
                        [
                            {offset: 0, color: '#2378f7'},
                            {offset: 0.7, color: '#2378f7'},
                            {offset: 1, color: '#83bff6'}
                        ]
                    )
                }
            },
            data: databar
        }
    ]
};



ops2 = {
    title : {
        text: '按年度统计字词总数统计图',
        subtext: '字数和词数累加统计'
    },
    tooltip : {
        trigger: 'axis',
        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
        }
    },
    legend: {
        data:['字总数','词总数']
    },
    toolbox: {
        show : true,
        feature : {
           
            magicType : {show: true, type: ['line', 'bar']},
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    xAxis : [ {
        type : 'category',
        data : ['1992年','\n1993年','1994年','\n1995年','1996年','\n1997年','1998年','\n1999年','2000年','\n2001年','2002年','\n2003年','2004年','\n2005年'],
    
        axisLabel: {
            interval:0,
            textStyle: {
                color: '#003366'
                }
            },
        axisTick: {
            show: false
        },
        axisLine: {
            show: false
        },
        splitLine: {show: false}

    }    ],
    yAxis : [
        {
            type : 'value'
        }
    ],
    series : [
        {
            name:'字总数',
            type:'bar',
            data:[923,1629,1884,2857,2423,1876,1618,1602,1683,2903,2390,2592,2351,2698],
            markPoint : {
                data : [
                    {type : 'max', name: '最大值'},
                    {type : 'min', name: '最小值'}
                ]
            },
            markLine : {
                data : [
                    {type : 'average', name: '平均值'}
                ]
            }
        },
        {
            name:'词总数',
            type:'bar',
            data:[1577,4385,5286,11732,9164,5016,4024,3746,4186,13107,9208,10865,8873,12208],
            markPoint : {
                data : [
                    {type : 'max', name: '最大值'},
                    {type : 'min', name: '最小值'}
                ]
            },
            markLine : {
                data : [
                    {type : 'average', name : '平均值'}
                ]
            }
        }
    ]
};
