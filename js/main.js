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
	$( "#birthday_click" ).click(function() {
		hide_div();
		birthday();
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

	$( "#home_click" ).click(function() {
		hide_div();
		$("#home").show();
		remove_class()
		$(this).addClass("active");
	});
});

function hide_div(){
	$("#home").hide();
	$("#gender").hide();
	$("#school").hide();
	$("#birthday").hide();
	$("#friendsstats").hide();
	$("#ratiopost").hide();

}

function remove_class(){
	$("#gender_click").removeClass("active");
	$("#birthday_click").removeClass("active");
	$("#home_click").removeClass("active");
	$("#school_click").removeClass("active");
	$("#friendsstats_click").removeClass("active");
	$("#ratiopost_click").removeClass("active");
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
	                text: 'Comparaison de votre nombre de post par rapport à celui de vos amis'
	            },
	            xAxis: {
	                categories: [
	                    'Catégories'                    
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
	                text: 'Top 10 de vos amis qui postent le plus'
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
	                text: 'Top 10 de vos amis qui postent le moins'
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
	                text: 'Comparaison de votre nombre de post par rapport à celui de vos amis'
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
	                text: 'Top 10 de vos amis qui postent le plus'
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
	                text: 'Top 10 de vos amis qui postent le moins'
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
function relationship()
	{
	$(function () {
	    var chart;
	    $(document).ready(function() {
	        var options = {    
	        		chart: {
	                renderTo: 'target',
	                plotBackgroundColor: null,
	                plotBorderWidth: null,
	                plotShadow: false,
	                type: 'pie'
	            },
	            title: {
	                text: 'Relationship'
	            },
	            tooltip: {
	        	    formatter: function() {
                    var s;
                    if (this.point.name) { // the pie chart
                        s = ''+
                            this.point.name +': '+ this.y +' personnes';
                           }
                    return s;
               }
	            	
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
	       					 action: 'relationship'
	   						 };
	   			$.ajax({
			        url: '../../../licornou/php/fblicornephp/stats.php',
			        type: 'POST', 
			        data: params,
			        cache: false,
			       dataType: 'json',

			        success: function(res) {
			        	options.series[0].data = res;
			            chart = new Highcharts.Chart(options);
			            $('#target').append("</br><center><button id='link_to_list'>Obtenir la liste des personnes célibataires</button><center>");
			            $( "#link_to_list" ).click(function() {
			            	var params = {
	       					 action: 'relationship_list'
	   							 };
 						   		$.ajax({
 						   			url: '../../../licornou/php/fblicornephp/stats.php',
			       				    type: 'POST', 
			          				data: params,
			        				cache: false,
			      					dataType: 'json',
 						   		  success: function(res){
 						   		  	$("#link_to_list").hide();
 						   		  	var count= Object.keys(res).length;
 						   			$('#target').append("<center><table border=1px;><tr><th>Femme</th><th>Homme</th></tr><td id='girl'></td><td id='boy'></td></tr></table></center>")

 						   			for(i=0; i<count; i=i+2)
 						   			{
 						   				if(res[i+1]["gender"]=="female"){
 						   					$('#girl').append(""+res[i]["name"]+"</br>")
 						   				}
 						   				else if(res[i+1]["gender"]=="male"){
 						   					$('#boy').append(""+res[i]["name"]+"</br>")
 						   				}
 						   			}
 						   		  }
 						   		});
						});
			            
	        									}
	   				 });
	        
	    });
	    
	});
	}