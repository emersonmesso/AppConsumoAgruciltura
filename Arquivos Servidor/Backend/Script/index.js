$(document).ready(function () {
    $("#btnModalSair").hide();
    var urlProcessos = "../Backend/Core/";
    $("#telaErros").hide();
    $("#imgLoader").hide();
    $("#telaSensor").hide();
    $("#telaComparacao").hide();
    //

    //Confirmação sair
    $("#btnConfirmSair").on('click', function () {
        $.post(urlProcessos + "execLogout.php", function () {
            location.reload();
        });
    });
    $("#btnHome").on('click', function () {
        window.location = "../";
    });

    $("#btnRelatorios").on('click', function () {
        window.location = "../relatorios";
    });

    var ctComp = document.getElementById("graficoComp").getContext('2d');
    var ctxM = document.getElementById("graficoMensal").getContext('2d');
    var ctxD = document.getElementById("graficoDiario").getContext('2d');

    var options = {
        chart: {
            id: 'consumo',
            type: 'radialBar',
            offsetY: -20
        },
        plotOptions: {
            radialBar: {
                startAngle: -90,
                endAngle: 90,
                track: {
                    background: "#DFDCDC",
                    strokeWidth: '97%',
                    margin: 5, // margin is in pixels
                    shadow: {
                        enabled: true,
                        top: 2,
                        left: 0,
                        color: '#999',
                        opacity: 1,
                        blur: 2
                    }
                },
                dataLabels: {
                    name: {
                        show: false
                    },
                    value: {
                        offsetY: -2,
                        fontSize: '22px'
                    }
                }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                shadeIntensity: 0.4,
                inverseColors: false,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 50, 53, 91]
            },
        },
        series: [0],
        labels: ['Average Results'],
    }
    var chart;

    //Buscando os dados do gráfico
    function buscaDados() {
        $.ajax({
            url: urlProcessos + 'buscaDadosGrafico.php',
            data: {},
            type: "POST",
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                options.series = [data.dados];
                chart = new ApexCharts(
                        document.querySelector(".circlechart"),
                        options
                        );
                chart.render();
            },
            error: function () {

            }
        });
    }

    function atualizaDados() {
        $.ajax({
            url: urlProcessos + 'buscaDadosGrafico.php',
            data: {},
            type: "POST",
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                options.series = [data.dados];
                ApexCharts.exec('consumo', 'updateOptions', options, false, true);
            },
            error: function () {
            }
        });
    }
    buscaDados();
    setInterval(function () {
        atualizaDados();
    }, 10000);

    var myLineComp = new Chart(ctComp, {
        type: 'line',
        fill: false,
        options: {
            legend: {
                labels: {
                    // This more specific font property overrides the global property
                    defaultFontFamily: 'sans-serif',
                    defaultFontSize: 30,
                    defaultFontStyle: "bold",
                    fontColor: 'black'
                }
            },
            scales: {
                xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Dias'
                        }
                    }],
                yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'litros'
                        }
                    }]
            },
            responsive: true
        }
    });
    var myLineChartM = new Chart(ctxM, {
        type: 'line',
        fill: false,
        options: {
            scales: {
                xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Dias'
                        }
                    }],
                yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'litros'
                        }
                    }]
            },
            responsive: true
        }
    });
    var myLineChartD = new Chart(ctxD, {
        type: 'line',
        fill: false,
        options: {
            scales: {
                xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Horas'
                        }
                    }],
                yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'litros'
                        }
                    }]
            },
            responsive: true
        }
    });

    //
    function removeData(chart) {
        chart.data.labels.pop();
        chart.data.datasets.forEach((dataset) => {
            dataset.data.pop();
        });
        chart.update();
    }
    function addData(chart, data) {
        chart.data = data;
        chart.update();
    }



    /*SELEÇÃO DO SENSOR*/
    $("#sensoresSelect").change(function () {
        $("#telaSensor").hide();
        var id = $(this).val();
        //adicionando as informações na comparação dos meses
        $("#compMeses").empty();
        $.ajax({
            url: urlProcessos + 'buscaMesesComp.php',
            data: {idSensor: id},
            type: "POST",
            dataType: "JSON",
            success: function (data) {
                $.each(data, function (id, valor) {
                    $('#compMeses').append(valor);
                });

            },
            error: function () {
                $("#telaErros").html("Não foi possível buscar dados do sensor");
                $("#telaErros").fadeIn(300);
            }
        });



        $("#idSensor").attr("lang", id);
        if (id != 0) {
            //buscando os dados dos sensor
            $.ajax({
                url: urlProcessos + "execBuscaDadosSensor.php",
                data: {id: id},
                type: "POST",
                dataType: "JSON",
                success: function (data) {

                    removeData(myLineChartM);
                    addData(myLineChartM, data.mesAtual);

                    removeData(myLineChartD);
                    addData(myLineChartD, data.diaAtual);

                    $("#telaSensor").fadeIn(200);

                },
                error: function () {
                    $("#telaErros").html("Não foi possível buscar dados do sensor");
                    $("#telaErros").fadeIn(300);
                }
            });
        }
    });

    /*COMPARAÇÃO DE MESES*/
    $("#compMeses").change(function () {
        $("#telaComparacao").hide();
        var mes = $(this).val();
        var idSensor = $("#idSensor").attr("lang");
        if (mes != 0) {
            $.ajax({
                url: urlProcessos + 'buscaCompara.php',
                data: {mes: mes, sensor: idSensor},
                dataType: "JSON",
                type: "POST",
                success: function (data) {
                    console.log(data);
                    dados = data;
                    removeData(myLineComp);
                    addData(myLineComp, dados);
                    $("#telaComparacao").show();
                },
                error: function () {
                    $("#telaErros").html("Não foi possível buscar dados do sensor");
                    $("#telaErros").fadeIn(300);
                }
            });
        }

    });
    /*COMPARAÇÃO DE MESES*/
});