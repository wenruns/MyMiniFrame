<?php foreach($data as $k =>$item) : ?>
    <?php if(is_array($item)) : ?>
        <?php view_layout("E:/wens/wens/testHtml/core/caches/views/978037c11149e64c17dd3f53eadcb5e2.php",['data'=>$item]) ?>
    <?php else : ?>
        <tr>
            <td style="box-sizing: border-box; padding: 0px 10px;width: 245px;"><?php echo htmlentities($k); ?></td>
            <td><?php echo htmlentities($item); ?></td>
        </tr>
    <?php endif; ?>
<?php endforeach; ?>