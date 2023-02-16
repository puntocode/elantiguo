var url = window.location.href;

/*---------------Chart Egresos-------------------*/
$.getJSON(url+"/egreso", function (result) {
    var json = result;
    var label = [];
    var jsondata = [];

    for (var i in json) {
        label.push(json[i].proveedor);
        jsondata.push(parseFloat(json[i].egreso));
    }

    console.log(json);

    var dougEgress = document.getElementById("doughnut-egresos");
    var dataEgress = {
        labels: label,
        datasets: [
            {
                data: jsondata,
                backgroundColor: [
                    "#9eaabd",
                    "#535B6D",
                    "#001432",
                    "#008BD6",
                    "#65A8FF",
                    "#90BAFF",
                    "#BAD4FF",
                    "#D8E7FF"
                ]
            }]
    };
    var egressDoughnutChart = new Chart(dougEgress, {
        type: 'doughnut',
        data: dataEgress,
        options: {
            animation: {
                animateScale: true,
                animateRotate: true
            },
            legend: {
                position: 'bottom',
                labels: {
                    fontSize: 10,
                    boxWidth: 15
                }
            },
            pieceLabel: {
                mode: 'percentage'
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        // obtiene la etiqueta de datos y el valor de datos para mostrar.
                        // convierte el valor de datos en cadena local para que utilice un número separado por comas
                        var dataLabel = data.labels[tooltipItem.index];
                        var value = ': ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

                        // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
                        if (Chart.helpers.isArray(dataLabel)) {
                            // show value on first line of multiline label
                            // need to clone because we are changing the value
                            dataLabel = dataLabel.slice();
                            dataLabel[0] += value;
                        } else {
                            dataLabel += value;
                        }

                        // return the text to display on the tooltip
                        return dataLabel;
                    }
                }
            }
        }
    });
});


/*---------------Chart Clientes-------------------*/
$.getJSON(url+"/clientes", function (result) {
    var json = result;
    var label = [];
    var jsondata = [];
    for (var i in json) {
        label.push(json[i].cliente);
        jsondata.push(parseFloat(json[i].ingreso));
    }

    console.log(jsondata);

    var dougCustomer = document.getElementById("doughnut-clientes");
    var dataCustomer = {
        labels: label,
        datasets: [
            {
                data: jsondata,
                backgroundColor: [
                    "#9eaabd",
                    "#535B6D",
                    "#001432",
                    "#008BD6",
                    "#65A8FF",
                    "#90BAFF",
                    "#BAD4FF",
                    "#D8E7FF"
                ]
            }]
    };
    var customerDoughnutChart = new Chart(dougCustomer, {
        type: 'doughnut',
        data: dataCustomer,
        options: {
            animation: {
                animateScale: true,
                animateRotate: true
            },
            legend: {
                position: 'bottom',
                labels: {
                    fontSize: 10,
                    boxWidth: 15
                }
            },
            pieceLabel: {
                mode: 'percentage'
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        // obtiene la etiqueta de datos y el valor de datos para mostrar.
                        // convierte el valor de datos en cadena local para que utilice un número separado por comas
                        var dataLabel = data.labels[tooltipItem.index];
                        var value = ': ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

                        // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
                        if (Chart.helpers.isArray(dataLabel)) {
                            // show value on first line of multiline label
                            // need to clone because we are changing the value
                            dataLabel = dataLabel.slice();
                            dataLabel[0] += value;
                        } else {
                            dataLabel += value;
                        }

                        // return the text to display on the tooltip
                        return dataLabel;
                    }
                }
            }
        }
    });
});

/*---------------Chart Productos-------------------*/
$.getJSON(url+"/productos", function (result) {
    var json = result;
    var label = [];
    var jsondata = [];
    for (var i in json) {
        label.push(json[i].producto);
        jsondata.push(parseFloat(json[i].ingreso));
    }

    console.log(jsondata);

    var dougProduct = document.getElementById("doughnut-productos");
    var dataProduct = {
        labels: label,
        datasets: [
            {
                data: jsondata,
                backgroundColor: [
                    "#9eaabd",
                    "#535B6D",
                    "#001432",
                    "#008BD6",
                    "#65A8FF",
                    "#90BAFF",
                    "#BAD4FF",
                    "#D8E7FF"
                ]
            }]
    };
    var productDoughnutChart = new Chart(dougProduct, {
        type: 'doughnut',
        data: dataProduct,
        options: {
            animation: {
                animateScale: true,
                animateRotate: true
            },
            legend: {
                position: 'bottom',
                labels: {
                    fontSize: 10,
                    boxWidth: 15
                }
            },
            pieceLabel: {
                mode: 'percentage'
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        // obtiene la etiqueta de datos y el valor de datos para mostrar.
                        // convierte el valor de datos en cadena local para que utilice un número separado por comas
                        var dataLabel = data.labels[tooltipItem.index];
                        var value = ': ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();

                        // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
                        if (Chart.helpers.isArray(dataLabel)) {
                            // show value on first line of multiline label
                            // need to clone because we are changing the value
                            dataLabel = dataLabel.slice();
                            dataLabel[0] += value;
                        } else {
                            dataLabel += value;
                        }

                        // return the text to display on the tooltip
                        return dataLabel;
                    }
                }
            }
        }
    });
});

/*---------------Chart Balance-------------------*/
$.getJSON(url+"/balance", function (result) {
    var json = result;
    var label = [];
    var jsondataIngreso = [];
    for (var i in json) {
        label.push(json[i].label);
        jsondataIngreso.push(parseFloat(json[i].Ingreso));

    }

    var jsondataEgreso = [];
    for (var i in json) {
        jsondataEgreso.push(parseFloat(json[i].Egreso));

    }

    var jsondataDiferencia = [];
    var diferencia = 0;
    for (var i in json) {
        ingreso = parseInt(json[i].Ingreso);
        egreso = parseInt(json[i].Egreso);
        diferencia = ingreso-egreso;
        jsondataDiferencia.push(diferencia);
    }

    var lineBalance = document.getElementById("line-balance");
    var dataBalance = {
        labels: label,
        datasets: [
            {
                label: "Ingreso",
                data: jsondataIngreso,
                borderColor: "#5cb85c",
                fill: false
            },
            {
                label: "Egreso",
                data: jsondataEgreso,
                borderColor: "#FA2A00",
                fill: false

            },
            {
                label: "Diferencia",
                data: jsondataDiferencia,
                borderColor: "#008bd6",
                fill: false
            }
        ]

    };

    var balanceLineChart = new Chart(lineBalance, {
        type: 'line',
        data: dataBalance,
        options: {
            animation: {
                animateScale: true,
                animateRotate: true
            },
            elements: {
                line: {
                    tension: 0
                }
            },
            legend: {
                position: 'bottom',
                labels: {
                    fontSize: 10,
                    boxWidth: 15
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        callback: function (value) {
                            return numeral(value).format('0,0');
                        }
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItems, data) {
                        return numeral(tooltipItems.yLabel).format('0,0');
                    }
                }
            }
        }

    });
});