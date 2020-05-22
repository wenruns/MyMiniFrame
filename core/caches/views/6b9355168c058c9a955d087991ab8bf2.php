<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>test</title>
</head>
<body>
<?php foreach($a as $k=>$vo) : ?>
    <table>
        <tr>
            <td><?php echo htmlentities($k); ?></td>
            <td><?php echo html_entity_decode( $vo ); ?></td>
        </tr>
    </table>
<?php endforeach; ?>

<?php foreach ( $a as $key =>$item ) : ?> <h2><?php echo htmlentities($key); ?>:<?php echo htmlentities($item); ?></h2><?php echo htmlentities(isset($a[1])?"hello":"bye"); ?>

<?php endforeach; ?>

<?php if(isset($a[456])) : ?>
    <h3>a[1]</h3>
<?php elseif(isset($b)) : ?>
    <h3>b</h3>
<?php else : ?>
    <h3>none</h3>
<?php endif; ?>


<?php /**  <?php echo dump($a); ?>  */ ?>

</body>
</html>
