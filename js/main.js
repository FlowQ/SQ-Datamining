/**
 * @author Constance Laborie
 */


$(document).ready(function(){ 

hide_div();
$("#home").show();

	$( "#gender_click" ).click(function() {
		hide_div();
		gender();
		$("#gender").show();
		$(this).addClass("active");
		$("#home_click").removeClass("active");
	});
	$( "#school_click" ).click(function() {
		hide_div();
		school_list();
		$("#school").show();
		remove_class()
		$(this).addClass("active");

	});
	$( "#country_click" ).click(function() {
		hide_div();
		country_current();
		country_origin();
		city_current();
		$("#country").show();
		remove_class()
		$(this).addClass("active");

	});
	$( "#birthday_click" ).click(function() {
		hide_div();
		birthday();
		sevenDays_Birthdays();
		$("#birthday").show();
		remove_class()
		$(this).addClass("active");
	});
	$( "#friendsstats_click" ).click(function() {
		hide_div();
		friendsstats_average();
		friendsstats_top10();
		friendsstats_low10();
		$("#friendsstats").show();
		remove_class();
		$(this).addClass("active");
	});
		$( "#ratiopost_click" ).click(function() {
		hide_div();
		ratiopost_average();
		ratiopost_top10();
		ratiopost_low10();
		$("#ratiopost").show();
		remove_class();
		$(this).addClass("active");
	});

	$( "#wallpost_click" ).click(function() {
		hide_div();
		wallpost_average();
		wallpost_top10();
		wallpost_low10();
		$("#wallpost").show();
		remove_class();
		$(this).addClass("active");
	});


	$( "#relation_click" ).click(function() {
		hide_div();
		relationship_count();
		couples_list();
		$("#relation").show();
		remove_class();
		$(this).addClass("active");
	});

	$( "#home_click" ).click(function() {
		hide_div();
		$("#home").show();
		remove_class();
		$(this).addClass("active");
	});
});

function hide_div(){
	$("#home").hide();
	$("#gender").hide();
	$("#wallpost").hide();
	$("#school").hide();
	$("#birthday").hide();
	$("#friendsstats").hide();
	$("#ratiopost").hide();
	$("#relation").hide();
	$("#country").hide();
}

function remove_class(){
	$("#gender_click").removeClass("active");
	$("#birthday_click").removeClass("active");
	$("#home_click").removeClass("active");
	$("#school_click").removeClass("active");
	$("#friendsstats_click").removeClass("active");
	$("#ratiopost_click").removeClass("active");
	$("#wallpost_click").removeClass("active");
	$("#relation_click").removeClass("active");
	$("#country_click").removeClass("active");
}

function getXMLHttpRequest() {
	var xhr = null;

	if (window.XMLHttpRequest || window.ActiveXObject) {
	  if (window.ActiveXObject) {
	    try {
	      xhr = new ActiveXObject("Msxml2.XMLHTTP");
	    } catch(e) {
	      xhr = new ActiveXObject("Microsoft.XMLHTTP");
	    }
	  } else {
	    xhr = new XMLHttpRequest(); 
	  }
	} else {
	  alert("Pas d'Ajax, tu fais pas le ménage, dommage!");
	  return null;
	}

	return xhr;
 }


function gender()
	{
	$(function () {
	    var chart;
	    $(document).ready(function() {
	        var options = {    
	        		chart: {
	                renderTo: 'gender_graph',
	                plotBackgroundColor: null,
	                plotBorderWidth: null,
	                plotShadow: false,
	                type: 'pie'
	            },
	            title: {
	                text: '% gender'
	            },
	            tooltip: {
	        	      formatter: function() {
                    var s;
                    if (this.point.name) { // the pie chart
                        s = ''+
                            this.point.name +': '+ this.y +' personnes';
                           }
                    return s;}
	            },
	            plotOptions: {
	                pie: {
	                    allowPointSelect: true,
	                    cursor: 'pointer',
	                    dataLabels: {
	                        enabled: true,
	                        color: '#000000',
	                        connectorColor: '#000000',
	                       formatter: function() {
	                            return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' %';
	                        }
	                    }
	                }
	            },
	            series: [{
	               data :[]
	            }]
	            }
	            
	    		var params = {
	       					 action: 'gender'
	   						 };
	   			$.ajax({
			        url: 'action.php',
			        type: 'POST', 
			        data: params,
			        cache: false,
			       dataType: 'json',

			        success: function(res) {
			        	options.series[0].data = res;
			            chart = new Highcharts.Chart(options);
	        									}
	   				 });
	        
	    });
	    
	});
	}
function school_list()
	{
		$(function () {
	    var chart;
	    $(document).ready(function() {
	         var options = {
	            chart: {
	                renderTo: 'school_graph',
	                type: 'column'
	            },
	            title: {
	                text: 'Age range'
	            },
	            subtitle: {
	                text: ''
	            },
	            xAxis: {
	                categories: [
	                    'Age'                    
	                ]
	            },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'Nombre de personnes'
	                }
	            },
	         /*   legend: {
	                layout: 'vertical',
	                backgroundColor: '#FFFFFF',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 300,
	                y: 70,
	                floating: true,
	                shadow: true
	            },*/
	            tooltip: {
	                formatter: function() {
	                    return ''+
	                        this.series.name + ': <b>'+ this.y+' fois</b>';
	                }
	            },
	            plotOptions: {
	                column: {
	                    pointPadding: 0.2,
	                    borderWidth: 0,
	                    dataLabels: {
	                        enabled: true,
	                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'grey'
	                    			}
	               			 }
	                
	           				 },
	           				 
	                series: [{
	    
	            }]
	            
	            }
	    		var params = {
	       					 action: 'list_school'
	   						 };
	   			$.ajax({
			        url: 'action.php',
			        type: 'POST',
			        data: params,
			        cache: false,
			        dataType: 'json',
			        success: function(points) {
			        	// $('#ajax-loading').hide();
	   					options.series = points;
			            chart = new Highcharts.Chart(options);
	        									}
	   				 });
	        });
	     
	    });
	    
	}	
function birthday()
	{
		$(function () {
	    var chart;
	    $(document).ready(function() {
	         var options = {
	            chart: {
	                renderTo: 'birthday_graph',
	                type: 'column'
	            },
	            title: {
	                text: 'Répartition des anniversaires par mois'
	            },
	            xAxis: {
	                categories: [
	                    'Mois'                    
	                ]
	            },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'Nombre d anniversaire'
	                }
	            },
	         /*   legend: {
	                layout: 'vertical',
	                backgroundColor: '#FFFFFF',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 300,
	                y: 70,
	                floating: true,
	                shadow: true
	            },*/
	            tooltip: {
	                formatter: function() {
	                    return ''+
	                        this.series.name + ': <b>'+ this.y+' fois</b>';
	                }
	            },
	            plotOptions: {
	                column: {
	                    pointPadding: 0.2,
	                    borderWidth: 0,
	                    dataLabels: {
	                        enabled: true,
	                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'grey'
	                    			}
	               			 }
	                
	           				 },
	           				 
	                series: [{
	    
	            }]
	            
	            }
	    		var params = {
	       					 action: 'birthday'
	   						 };
	   			$.ajax({
			        url: 'action.php',
			        type: 'POST',
			        data: params,
			        cache: false,
			        dataType: 'json',
			        success: function(points) {
			        	 $('#ajax-loading').hide();
	   					options.series = points;
			            chart = new Highcharts.Chart(options);
	        									}
	   				 });
	        });
	     
	    });
	    
	}
function friendsstats_average()
	{
		$(function () {
	    var chart;
	    $(document).ready(function() {
	         var options = {
	            chart: {
	                renderTo: 'friendsstats_graph_average',
	                type: 'column'
	            },
	            title: {
	                text: 'Comparaison de votre nombre d amis par rapport au nombre d amis de vos amis'
	            },
	            xAxis: {
	                categories: [
	                    'Catégories'                    
	                ]
	            },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'Nombre d amis'
	                }
	            },
	         /*   legend: {
	                layout: 'vertical',
	                backgroundColor: '#FFFFFF',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 300,
	                y: 70,
	                floating: true,
	                shadow: true
	            },*/
	            tooltip: {
	                formatter: function() {
	                    return ''+
	                        this.series.name + ': <b>'+ this.y+' amis</b>';
	                }
	            },
	            plotOptions: {
	                column: {
	                    pointPadding: 0.2,
	                    borderWidth: 0,
	                    dataLabels: {
	                        enabled: true,
	                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'grey'
	                    			}
	               			 }
	                
	           				 },
	           				 
	                series: [{
	    
	            }]
	            
	            }
	    		var params = {
	       					 action: 'friendsstats_average'
	   						 };
	   			$.ajax({
			        url: 'action.php',
			        type: 'POST',
			        data: params,
			        cache: false,
			        dataType: 'json',
			        success: function(points) {
	   					options.series = points;
			            chart = new Highcharts.Chart(options);
	        									}
	   				 });
	        });
	     
	    });
	    
	}
function friendsstats_top10()
	{
		$(function () {
	    var chart;
	    $(document).ready(function() {
	         var options = {
	            chart: {
	                renderTo: 'friendsstats_graph_top10',
	                type: 'column'
	            },
	            title: {
	                text: 'Top 10 de vos amis qui ont le plus d amis'
	            },
	            xAxis: {
	                categories: [
	                    'Amis'                    
	                ]
	            },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'Nombre d amis'
	                }
	            },
	         /*   legend: {
	                layout: 'vertical',
	                backgroundColor: '#FFFFFF',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 300,
	                y: 70,
	                floating: true,
	                shadow: true
	            },*/
	            tooltip: {
	                formatter: function() {
	                    return ''+
	                        this.series.name + ': <b>'+ this.y+' amis</b>';
	                }
	            },
	            plotOptions: {
	                column: {
	                    pointPadding: 0.2,
	                    borderWidth: 0,
	                    dataLabels: {
	                        enabled: true,
	                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'grey'
	                    			}
	               			 }
	                
	           				 },
	           				 
	                series: [{
	    
	            }]
	            
	            }
	    		var params = {
	       					 action: 'friendsstats_top10'
	   						 };
	   			$.ajax({
			        url: 'action.php',
			        type: 'POST',
			        data: params,
			        cache: false,
			        dataType: 'json',
			        success: function(points) {
	   					options.series = points;
			            chart = new Highcharts.Chart(options);
	        									}
	   				 });
	        });
	     
	    });
	    
	}
function friendsstats_low10()
	{
		$(function () {
	    var chart;
	    $(document).ready(function() {
	         var options = {
	            chart: {
	                renderTo: 'friendsstats_graph_low10',
	                type: 'column'
	            },
	            title: {
	                text: 'Top 10 de vos amis qui ont le moins d amis'
	            },
	            xAxis: {
	                categories: [
	                    'Amis'                    
	                ]
	            },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'Nombre de post'
	                }
	            },
	         /*   legend: {
	                layout: 'vertical',
	                backgroundColor: '#FFFFFF',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 300,
	                y: 70,
	                floating: true,
	                shadow: true
	            },*/
	            tooltip: {
	                formatter: function() {
	                    return ''+
	                        this.series.name + ': <b>'+ this.y+' fois</b>';
	                }
	            },
	            plotOptions: {
	                column: {
	                    pointPadding: 0.2,
	                    borderWidth: 0,
	                    dataLabels: {
	                        enabled: true,
	                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'grey'
	                    			}
	               			 }
	                
	           				 },
	           				 
	                series: [{
	    
	            }]
	            
	            }
	    		var params = {
	       					 action: 'friendsstats_low10'
	   						 };
	   			$.ajax({
			        url: 'action.php',
			        type: 'POST',
			        data: params,
			        cache: false,
			        dataType: 'json',
			        success: function(points) {
	   					options.series = points;
			            chart = new Highcharts.Chart(options);
	        									}
	   				 });
	        });
	     
	    });
	    
	}
function ratiopost_average()
	{
		$(function () {
	    var chart;
	    $(document).ready(function() {
	         var options = {
	            chart: {
	                renderTo: 'ratiopost_graph_average',
	                type: 'column'
	            },
	            title: {
	                text: 'Ratio nombre de post/nombre d amis'
	            },
	            xAxis: {
	                categories: [
	                    'Catégories'                    
	                ]
	            },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'ratio'
	                }
	            },
	         /*   legend: {
	                layout: 'vertical',
	                backgroundColor: '#FFFFFF',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 300,
	                y: 70,
	                floating: true,
	                shadow: true
	            },*/
	            tooltip: {
	                formatter: function() {
	                    return ''+
	                        this.series.name + ': <b>'+ this.y+' </b>';
	                }
	            },
	            plotOptions: {
	                column: {
	                    pointPadding: 0.2,
	                    borderWidth: 0,
	                    dataLabels: {
	                        enabled: true,
	                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'grey'
	                    			}
	               			 }
	                
	           				 },
	           				 
	                series: [{
	    
	            }]
	            
	            }
	    		var params = {
	       					 action: 'ratiopost_average'
	   						 };
	   			$.ajax({
			        url: 'action.php',
			        type: 'POST',
			        data: params,
			        cache: false,
			        dataType: 'json',
			        success: function(points) {
	   					options.series = points;
			            chart = new Highcharts.Chart(options);
	        									}
	   				 });
	        });
	     
	    });
	    
	}
function ratiopost_top10()
	{
		$(function () {
	    var chart;
	    $(document).ready(function() {
	         var options = {
	            chart: {
	                renderTo: 'ratiopost_graph_top10',
	                type: 'column'
	            },
	            title: {
	                text: 'Top 10 de vos amis qui ont le plus grand ratio Nombre de post/nombre d amis'
	            },
	            xAxis: {
	                categories: [
	                    'Amis'                    
	                ]
	            },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'ratio'
	                }
	            },
	         /*   legend: {
	                layout: 'vertical',
	                backgroundColor: '#FFFFFF',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 300,
	                y: 70,
	                floating: true,
	                shadow: true
	            },*/
	            tooltip: {
	                formatter: function() {
	                    return ''+
	                        this.series.name + ': <b>'+ this.y+'</b>';
	                }
	            },
	            plotOptions: {
	                column: {
	                    pointPadding: 0.2,
	                    borderWidth: 0,
	                    dataLabels: {
	                        enabled: true,
	                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'grey'
	                    			}
	               			 }
	                
	           				 },
	           				 
	                series: [{
	    
	            }]
	            
	            }
	    		var params = {
	       					 action: 'ratiopost_top10'
	   						 };
	   			$.ajax({
			        url: 'action.php',
			        type: 'POST',
			        data: params,
			        cache: false,
			        dataType: 'json',
			        success: function(points) {
	   					options.series = points;
			            chart = new Highcharts.Chart(options);
	        									}
	   				 });
	        });
	     
	    });
	    
	}
function ratiopost_low10()
	{
		$(function () {
	    var chart;
	    $(document).ready(function() {
	         var options = {
	            chart: {
	                renderTo: 'ratiopost_graph_low10',
	                type: 'column'
	            },
	            title: {
	                text: 'Top 10 de vos amis qui ont le plus petit ratio Nombre de post/nombre d amis'
	            },
	            xAxis: {
	                categories: [
	                    'Amis'                    
	                ]
	            },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'Ratio'
	                }
	            },
	         /*   legend: {
	                layout: 'vertical',
	                backgroundColor: '#FFFFFF',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 300,
	                y: 70,
	                floating: true,
	                shadow: true
	            },*/
	            tooltip: {
	                formatter: function() {
	                    return ''+
	                        this.series.name + ': <b>'+ this.y+'</b>';
	                }
	            },
	            plotOptions: {
	                column: {
	                    pointPadding: 0.2,
	                    borderWidth: 0,
	                    dataLabels: {
	                        enabled: true,
	                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'grey'
	                    			}
	               			 }
	                
	           				 },
	           				 
	                series: [{
	    
	            }]
	            
	            }
	    		var params = {
	       					 action: 'ratiopost_low10'
	   						 };
	   			$.ajax({
			        url: 'action.php',
			        type: 'POST',
			        data: params,
			        cache: false,
			        dataType: 'json',
			        success: function(points) {
	   					options.series = points;
			            chart = new Highcharts.Chart(options);
	        									}
	   				 });
	        });
	     
	    });
	    
	}
function wallpost_average()
	{
		$(function () {
	    var chart;
	    $(document).ready(function() {
	         var options = {
	            chart: {
	                renderTo: 'wallpost_graph_average',
	                type: 'column'
	            },
	            title: {
	                text: 'Nombre de post écrit par tes amis sur ton mur'
	            },
	            xAxis: {
	                categories: [
	                    'Catégories'                    
	                ]
	            },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'Posts'
	                }
	            },
	         /*   legend: {
	                layout: 'vertical',
	                backgroundColor: '#FFFFFF',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 300,
	                y: 70,
	                floating: true,
	                shadow: true
	            },*/
	            tooltip: {
	                formatter: function() {
	                    return ''+
	                        this.series.name + ': <b>'+ this.y+' post </b>';
	                }
	            },
	            plotOptions: {
	                column: {
	                    pointPadding: 0.2,
	                    borderWidth: 0,
	                    dataLabels: {
	                        enabled: true,
	                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'grey'
	                    			}
	               			 }
	                
	           				 },
	           				 
	                series: [{
	    
	            }]
	            
	            }
	    		var params = {
	       					 action: 'wallpost_average'
	   						 };
	   			$.ajax({
			        url: 'action.php',
			        type: 'POST',
			        data: params,
			        cache: false,
			        dataType: 'json',
			        success: function(points) {
	   					options.series = points;
			            chart = new Highcharts.Chart(options);
	        									}
	   				 });
	        });
	     
	    });
	    
	}
function wallpost_top10()
	{
		$(function () {
	    var chart;
	    $(document).ready(function() {
	         var options = {
	            chart: {
	                renderTo: 'wallpost_graph_top10',
	                type: 'column'
	            },
	            title: {
	                text: 'Top 10 de vos amis qui ont le plus de post écrit sur leur mur'
	            },
	            xAxis: {
	                categories: [
	                    'Amis'                    
	                ]
	            },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'Posts'
	                }
	            },
	         /*   legend: {
	                layout: 'vertical',
	                backgroundColor: '#FFFFFF',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 300,
	                y: 70,
	                floating: true,
	                shadow: true
	            },*/
	            tooltip: {
	                formatter: function() {
	                    return ''+
	                        this.series.name + ': <b>'+ this.y+' posts</b>';
	                }
	            },
	            plotOptions: {
	                column: {
	                    pointPadding: 0.2,
	                    borderWidth: 0,
	                    dataLabels: {
	                        enabled: true,
	                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'grey'
	                    			}
	               			 }
	                
	           				 },
	           				 
	                series: [{
	    
	            }]
	            
	            }
	    		var params = {
	       					 action: 'wallpost_top10'
	   						 };
	   			$.ajax({
			        url: 'action.php',
			        type: 'POST',
			        data: params,
			        cache: false,
			        dataType: 'json',
			        success: function(points) {
	   					options.series = points;
			            chart = new Highcharts.Chart(options);
	        									}
	   				 });
	        });
	     
	    });
	    
	}
function wallpost_low10()
	{
		$(function () {
	    var chart;
	    $(document).ready(function() {
	         var options = {
	            chart: {
	                renderTo: 'wallpost_graph_low10',
	                type: 'column'
	            },
	            title: {
	                text: 'Top 10 de vos amis qui ont le moins de post écrit sur leur mur'
	            },
	            xAxis: {
	                categories: [
	                    'Amis'                    
	                ]
	            },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'Posts'
	                }
	            },
	         /*   legend: {
	                layout: 'vertical',
	                backgroundColor: '#FFFFFF',
	                align: 'left',
	                verticalAlign: 'top',
	                x: 300,
	                y: 70,
	                floating: true,
	                shadow: true
	            },*/
	            tooltip: {
	                formatter: function() {
	                    return ''+
	                        this.series.name + ': <b>'+ this.y+' posts</b>';
	                }
	            },
	            plotOptions: {
	                column: {
	                    pointPadding: 0.2,
	                    borderWidth: 0,
	                    dataLabels: {
	                        enabled: true,
	                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'grey'
	                    			}
	               			 }
	                
	           				 },
	           				 
	                series: [{
	    
	            }]
	            
	            }
	    		var params = {
	       					 action: 'wallpost_low10'
	   						 };
	   			$.ajax({
			        url: 'action.php',
			        type: 'POST',
			        data: params,
			        cache: false,
			        dataType: 'json',
			        success: function(points) {
	   					options.series = points;
			            chart = new Highcharts.Chart(options);
	        									}
	   				 });
	        });
	     
	    });
	    
	}
function sevenDays_Birthdays() 
	{
	var req = getXMLHttpRequest();
	req.onreadystatechange = function() {
	  if (req.readyState == 4 && (req.status == 200 || req.status == 0)) {
	  }
	};
	req.open("POST", "action.php",true);
	req.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	req.send('action=sevenDays_Birthdays');
	req.onreadystatechange = function() {
    if (req.readyState == 4) {
      document.getElementById("birthday_list").innerHTML = req.responseText;
    }
  }
}
function relationship_count()
{
	$(function () {
	    var chart;
	    $(document).ready(function() {
	        var options = {    
	        		chart: {
	                renderTo: 'relation_graph',
	                plotBackgroundColor: null,
	                plotBorderWidth: null,
	                plotShadow: false,
	                type: 'pie'
	            },
	            title: {
	                text: '% gender'
	            },
	            tooltip: {
	        	    pointFormat: '{point.percentage}%</b>',
	            	percentageDecimals: 1
	            },
	            plotOptions: {
	                pie: {
	                    allowPointSelect: true,
	                    cursor: 'pointer',
	                    dataLabels: {
	                        enabled: true,
	                        color: '#000000',
	                        connectorColor: '#000000',
	                       formatter: function() {
	                            return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' %';
	                        }
	                    }
	                }
	            },
	            series: [{
	               data :[]
	            }]
	            }
	            
	    		var params = {
	       					 action: 'relationship_count'
	   						 };
	   			$.ajax({
			        url: 'action.php',
			        type: 'POST', 
			        data: params,
			        cache: false,
			       dataType: 'json',

			        success: function(res) {
			        	options.series[0].data = res;
			            chart = new Highcharts.Chart(options);
	        									}
	   				 });
	        
	    });
	    
	});
}

function couples_list() {
	var req = getXMLHttpRequest();
	req.onreadystatechange = function() {
	  if (req.readyState == 4 && (req.status == 200 || req.status == 0)) {
	  }
	};
	req.open("POST", "action.php", true);
	req.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	req.send("action=couples_list");
	req.onreadystatechange = function() {
    if (req.readyState == 4) {
      document.getElementById("couples_list").innerHTML = req.responseText;
    }
  }
}

function country_current()
	{
	$(function () {
	    var chart;
	    $(document).ready(function() {
	        var options = {    
	        		chart: {
	                renderTo: 'country_graph_current',
	                plotBackgroundColor: null,
	                plotBorderWidth: null,
	                plotShadow: false,
	                type: 'pie'
	            },
	            title: {
	                text: 'Pays actuel'
	            },
	            tooltip: {
	        	    formatter: function() {
                    var s;
                    if (this.point.name) { // the pie chart
                        s = ''+
                            this.point.name +': '+ this.y +' personnes';
                           }
                    return s;}
	            },
	            plotOptions: {
	                pie: {
	                    allowPointSelect: true,
	                    cursor: 'pointer',
	                    dataLabels: {
	                        enabled: true,
	                        color: '#000000',
	                        connectorColor: '#000000',
	                       formatter: function() {
	                            return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' %';
	                        }
	                    }
	                }
	            },
	            series: [{
	               data :[]
	            }]
	            }
	            
	    		var params = {
	       					 action: 'country_current'
	   						 };
	   			$.ajax({
			        url: 'action.php',
			        type: 'POST', 
			        data: params,
			        cache: false,
			       dataType: 'json',

			        success: function(res) {
			        	options.series[0].data = res;
			            chart = new Highcharts.Chart(options);
	        									}
	   				 });
	        
	    });
	    
	});
	}
function country_origin()
	{
	$(function () {
	    var chart;
	    $(document).ready(function() {
	        var options = {    
	        		chart: {
	                renderTo: 'country_graph_origin',
	                plotBackgroundColor: null,
	                plotBorderWidth: null,
	                plotShadow: false,
	                type: 'pie'
	            },
	            title: {
	                text: 'Pays d origine'
	            },
	            tooltip: {
	        	      formatter: function() {
                    var s;
                    if (this.point.name) { // the pie chart
                        s = ''+
                            this.point.name +': '+ this.y +' personnes';
                           }
                    return s;}
	            },
	            plotOptions: {
	                pie: {
	                    allowPointSelect: true,
	                    cursor: 'pointer',
	                    dataLabels: {
	                        enabled: true,
	                        color: '#000000',
	                        connectorColor: '#000000',
	                       formatter: function() {
	                            return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' %';
	                        }
	                    }
	                }
	            },
	            series: [{
	               data :[]
	            }]
	            }
	            
	    		var params = {
	       					 action: 'country_origin'
	   						 };
	   			$.ajax({
			        url: 'action.php',
			        type: 'POST', 
			        data: params,
			        cache: false,
			       dataType: 'json',

			        success: function(res) {
			        	options.series[0].data = res;
			            chart = new Highcharts.Chart(options);
	        									}
	   				 });
	        
	    });
	    
	});
	}
function city_current()
	{
	$(function () {
	    var chart;
	    $(document).ready(function() {
	        var options = {    
	        		chart: {
	                renderTo: 'city_graph_current',
	                plotBackgroundColor: null,
	                plotBorderWidth: null,
	                plotShadow: false,
	                type: 'pie'
	            },
	            title: {
	                text: 'Ville actuelle'
	            },
	            tooltip: {
	        	      formatter: function() {
                    var s;
                    if (this.point.name) { // the pie chart
                        s = ''+
                            this.point.name +': '+ this.y +' personnes';
                           }
                    return s;}
	            },
	            plotOptions: {
	                pie: {
	                    allowPointSelect: true,
	                    cursor: 'pointer',
	                    dataLabels: {
	                        enabled: true,
	                        color: '#000000',
	                        connectorColor: '#000000',
	                       formatter: function() {
	                            return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' %';
	                        }
	                    }
	                }
	            },
	            series: [{
	               data :[]
	            }]
	            }
	            
	    		var params = {
	       					 action: 'city_current'
	   						 };
	   			$.ajax({
			        url: 'action.php',
			        type: 'POST', 
			        data: params,
			        cache: false,
			       dataType: 'json',

			        success: function(res) {
			        	options.series[0].data = res;
			            chart = new Highcharts.Chart(options);
	        									}
	   				 });
	        
	    });
	    
	});
}