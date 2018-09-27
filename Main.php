<?php

class Main
{
    public static function run() {

        // PSEUDOCODE
        // 1. Parse dataset.csv into a structure we can use
        // 2. Tokenize Input.txt and generate an AST
        // 3. Evaluate AST and generate the javascript + html for each individual chart
        // 4. Group chart html if applicable
        // 5. Return HTML and javascript back to index.php

        // Example code for proof of concept
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js'></script>
        </head>
        <body>
        
        <canvas id='myChart' width='100' height='100'></canvas>
        
        </body>
        </html>
        
        <script>
        var ctx = document.getElementById(\"myChart\");
        var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [\"Red\", \"Blue\", \"Yellow\", \"Green\", \"Purple\", \"Orange\"],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
        });
        </script>";

        return $html;
    }
}