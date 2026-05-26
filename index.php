<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Serre - Capteurs</title>
    <link rel="icon" href="data:,">
    <script src="/projet/js/anychart-base.min.js"></script>
    <script src="/projet/js/anychart-ui.min.js"></script>
    <script src="/projet/js/anychart-data-adapter.min.js"></script>
    <script src="/projet/js/anychart-exports.min.js"></script>
    <link href="/projet/css/anychart-ui.min.css" type="text/css" rel="stylesheet">
    <link href="/projet/css/anychart-ui.css" type="text/css" rel="stylesheet">
    <link href="container.css" type="text/css" rel="stylesheet">
</head>
<body>
<div id="container"></div>
<script>
anychart.data.loadJsonFile("data.php", function (data) {
    var dataSet = anychart.data.set(data);

    var seriesData_1 = dataSet.mapAs({'x': 0, 'value': 1}); // température
    var seriesData_2 = dataSet.mapAs({'x': 0, 'value': 2}); // humidité
    var seriesData_3 = dataSet.mapAs({'x': 0, 'value': 3}); // humidité sol

    var chart = anychart.area();
    chart.animation(true);
    chart.yScale().stackMode('value');
    chart.crosshair().enabled(true).yLabel().enabled(false);
    chart.crosshair().enabled(true).xLabel().enabled(false);
    chart.crosshair().yStroke(null).xStroke('#fff').zIndex(99);

    var setupSeries = function (series, name) {
        series.stroke('3 #fff 1');
        series.fill(function () { return this.sourceColor + ' 0.8'; });
        series.name(name);
        series.markers().zIndex(100);
        series.clip(false);
        series.hovered()
            .stroke('3 #fff 1')
            .markers().enabled(true).type('circle').size(4).stroke('1.5 #fff');
    };

    var series;
    series = chart.area(seriesData_1);
    setupSeries(series, 'Température');

    series = chart.area(seriesData_2);
    setupSeries(series, 'Humidité');

    series = chart.area(seriesData_3);
    setupSeries(series, 'Humidité Sol');

    chart.interactivity().hoverMode('by-x');
    chart.tooltip().displayMode('union');
    chart.legend().enabled(true).fontSize(13).padding([0, 0, 25, 0]);
    chart.title("Serre - 3 Capteurs");
    chart.container('container');
    chart.draw();

    // Mise à jour toutes les 5 secondes
    setInterval(function() {
        anychart.data.loadJsonFile("data.php", function (data) {
            dataSet.data(data);
        });
    }, 5000);
});
</script>
</body>
</html>
