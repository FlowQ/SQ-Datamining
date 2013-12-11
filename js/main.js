/**
 * @author Constance Laborie
 */


$(document).ready(function(){ 
	 $('#ajax-loading').hide();
	 $('#filters select').change(function () {
	   if($( "#filters select" ).val()=="gender")
	   {
	   	gender();
	   }
	   else if($( "#filters select" ).val()=="top")
	   {
	   	$('#target').empty();
	   	$('#ajax-loading').show();
	   	top_like();
	   
	   }   
	   else if($("#filters select").val()=="relationship")
	   {
	   	relationship();
	   } 
	    else if($("#filters select").val()=="age_range")
	   {
	   	age_range();
	   //	alert("En contruction...")
	   } 
	    })
	
});


function gender()
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
			        url: '../../../licornou/php/fblicornephp/stats.php',
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
function age_range()
	{
		$(function () {
	    var chart;
	    $(document).ready(function() {
	         var options = {
	            chart: {
	                renderTo: 'target',
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
	       					 action: 'age_range'
	   						 };
	   			$.ajax({
			        url: '../../../licornou/php/fblicornephp/stats.php',
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
function top_like()
	{
		$(function () {
	    var chart;
	    $(document).ready(function() {
	         var options = {
	            chart: {
	                renderTo: 'target',
	                type: 'column'
	            },
	            title: {
	                text: 'Top 10 like on my wall'
	            },
	            subtitle: {
	                text: 'Tout types de statut'
	            },
	            xAxis: {
	                categories: [
	                    'Nom'                    
	                ]
	            },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: 'Nombre de like'
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
	       					 action: 'top'
	   						 };
	   			$.ajax({
			        url: '../../../licornou/php/fblicornephp/stats.php',
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