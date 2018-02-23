<script>

var valueLabelWidth = 40; // space reserved for value labels (right)
var barHeight = 20; // height of one bar
var barLabelWidth = 30; // space reserved for bar labels
var barLabelPadding = 5; // padding between bar and bar labels (left)
var gridLabelHeight = 50; // space reserved for gridline labels
var gridChartOffset = 3; // space between start of grid and first bar
var maxBarWidth = 600; // width of the bar with the max value

d3.csv("data.csv", function(error, data) {

    data.forEach(function(d) {
        d.id = +d.id;
        d.training_time = +d.training_time;
    });
// accessor functions 
var barLabel = function(d) { return d['id']; };
var barValue = function(d) { return parseFloat(d['training_time']); };
 
// scales
var yScale = d3.scale.ordinal().domain(d3.range(0, data.length)).rangeBands([0, data.length * barHeight]);
var y = function(d, i) { return yScale(i); };
var yText = function(d, i) { return y(d, i) + yScale.rangeBand() / 2; };
var x = d3.scale.linear().domain([0, d3.max(data, barValue)]).range([0, maxBarWidth]);
// svg container element
var chart = d3.select('#graph2').append("svg")
  .attr('width', maxBarWidth + barLabelWidth + valueLabelWidth)
  .attr('height', 100 + data.length * barHeight);
// grid line labels
var gridContainer = chart.append('g')
  .attr('transform', 'translate(' + barLabelWidth + ',' + gridLabelHeight + ')'); 
gridContainer.selectAll("text").data(x.ticks(10)).enter().append("text")
  .attr("x", x)
  .attr("dy", -3)
  .attr("text-anchor", "middle")
  .text(String);
// vertical grid lines
gridContainer.selectAll("line").data(x.ticks(10)).enter().append("line")
  .attr("x1", x)
  .attr("x2", x)
  .attr("y1", 0)
  .attr("y2", yScale.rangeExtent()[1] + gridChartOffset)
  .style("stroke", "#ccc");
// bar labels
var labelsContainer = chart.append('g')
  .attr('transform', 'translate(' + (barLabelWidth - barLabelPadding) + ',' + (gridLabelHeight + gridChartOffset) + ')'); 
labelsContainer.selectAll('text').data(data).enter().append('text')
  //.attr('y', yText)
  //.attr('stroke', 'none')
  //.attr('fill', 'black')
  //.attr("dy", ".35em") // vertical-align: middle
  //.attr('text-anchor', 'end')
  //.text(barLabel);
// bars
var barsContainer = chart.append('g')
  .attr('transform', 'translate(' + barLabelWidth + ',' + (gridLabelHeight + gridChartOffset) + ')'); 
barsContainer.selectAll("rect").data(data).enter().append("rect")
  .attr('y', y)
  .attr('height', yScale.rangeBand())
  .attr('width', function(d) { return x(barValue(d)); })
  .attr('stroke', 'white')
  .attr('fill', 'steelblue');
// bar value labels
barsContainer.selectAll("text").data(data).enter().append("text")
  .attr("x", function(d) { return x(barValue(d)); })
  .attr("y", yText)
  .attr("dx", 3) // padding-left
  .attr("dy", ".35em") // vertical-align: middle
  .attr("text-anchor", "start") // text-align: right
  .attr("fill", "black")
  .attr("stroke", "none")
  .text(function(d) { return d3.round(barValue(d), 2); });
// start line
barsContainer.append("line")
  .attr("y1", -gridChartOffset)
  .attr("y2", yScale.rangeExtent()[1] + gridChartOffset)
  .style("stroke", "#000");

//x axis label
chart.append("text")             
      .attr("transform",
            "translate(" + (maxBarWidth/2) + " ," + 
                           ( 20 ) + ")")
      .style("text-anchor", "middle")
      .text("Training Time[minutes]");

//y axis label
chart.append("text")
      .attr("transform", "rotate(-90)")
      .attr("y", 0)
      .attr("x",0 - 120)
      .attr("dy", "1em")
      .style("text-anchor", "middle")
      .text("Trainings"); 
});

</script>