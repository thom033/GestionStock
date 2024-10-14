<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Données détaillées  <?php if (isset($date)) {
        echo htmlspecialchars($date);
    } ?>
    </title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <form action="<?php echo base_url(); ?>Stock_ctr/recherche" method="get">
        <input type="date" name="date" id="">
        <button type="submit"> rechercher </button>
    </form>
    
    <?php 
        if (isset($date)) {
            echo ('<br>');  
            echo ('<br>input ');
            var_dump($inputs);
            echo ('<br>');
            echo ('<br>output ');
            var_dump($outputs);
            echo ('<br>');
            echo ('<br>stock ');
            var_dump($stocks);
            echo ('<br>');
        }
    ?>
    
    <?php if (isset($date)) : ?>
        <h1>Données détaillées pour la date : <?php echo htmlspecialchars($date); ?></h1>
        <table>
            <thead>
                <tr>
                    <th colspan="3">Input</th>
                    <th colspan="3">Output</th>
                    <th colspan="3">Stock</th>
                </tr>
                <tr>  
                    <th>QTT</th>
                    <th>PU</th>
                    <th>Total</th>
                    <th>QTT</th>
                    <th>PU</th>
                    <th>Total</th>
                    <th>QTT</th>
                    <th>PU</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $max_rows = max(count($inputs), count($outputs), count($stocks)); 
                for ($i = 0; $i < $max_rows; $i++) {
                ?>
                <tr>
                    <!-- Inputs -->
                    <td><?= isset($inputs[$i]['qtt']) ? $inputs[$i]['qtt'] : '' ?></td>
                    <td><?= isset($inputs[$i]['pu']) ? $inputs[$i]['pu'] : '' ?></td>
                    <td><?= isset($inputs[$i]['total']) ? $inputs[$i]['total'] : '' ?></td>

                    <!-- Outputs -->
                    <td><?= isset($outputs[$i]['qtt']) ? $outputs[$i]['qtt'] : '' ?></td>
                    <td><?= isset($outputs[$i]['pu']) ? $outputs[$i]['pu'] : '' ?></td>
                    <td><?= isset($outputs[$i]['total']) ? $outputs[$i]['total'] : '' ?></td>

                    <!-- Stocks -->
                    <td><?= isset($stocks[$i]['qtt']) ? $stocks[$i]['qtt'] : '' ?></td>
                    <td><?= isset($stocks[$i]['pu']) ? $stocks[$i]['pu'] : '' ?></td>
                    <td><?= isset($stocks[$i]['total']) ? $stocks[$i]['total'] : '' ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
