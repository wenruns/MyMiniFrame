<?php if(is_array($data)) : ?>
    <?php if(count($data)) : ?>
        <?php if($enter) : ?>
            <p><?php echo htmlentities(gettype($data)); ?>(<?php echo htmlentities(count($data)); ?>) {</p>
        <?php else : ?>
            <span><?php echo htmlentities(gettype($data)); ?>(<?php echo htmlentities(count($data)); ?>) {</span>
        <?php endif; ?>
        <ul>
            <?php foreach($data as $k=>$vo) : ?>
                <?php if(is_array($vo)) : ?>
                    <?php if(empty($vo)) : ?>
                        <li class="arr-next"><span>"<?php echo htmlentities($k); ?>"</span> <span class="arrowhead"></span> array(0){}</li>
                    <?php else : ?>
                        <li class="arr-next">
                            <span>"<?php echo htmlentities($k); ?>"</span><span
                                    class="arrowhead"></span></span> <?php view_layout("E:/wens/wens/testHtml/core/caches/views/2fbb9de24cf4ab039fc90cdfc1b2f296.php",['data'=>$vo,'enter'=>false]) ?>
                        </li>
                    <?php endif; ?>
                <?php else : ?>
                    <li class="arr-next">
                        "<?php echo htmlentities($k); ?>" <span class="arrowhead"></span></span>
                        <?php switch(gettype($vo)): ?><?php case 'integer': ?>int(<?php echo htmlentities(strlen($vo)); ?>) <?php echo htmlentities($vo); ?>
                            <?php break; ?><?php case 'object': ?><?php echo htmlentities(var_dump($vo)); ?>
                            <?php break; ?><?php case 'boolean': ?>boolean(<?php echo htmlentities($vo ? 'true' : 'false'); ?>)
                            <?php break; ?>;
                            <?php case 'NULL': ?>NULL
                            <?php break; ?>;
                            <?php default : ?>
                            <?php echo htmlentities(gettype($vo)); ?>(<?php echo htmlentities(strlen($vo)); ?>) "<?php echo htmlentities($vo); ?>"
                        <?php endswitch; ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        <?php if(is_array($data) && $enter) : ?>
            <li>}</li>
        <?php elseif(is_array($data)) : ?>
            <li class="arr-next">}</li>
        <?php endif; ?>
    <?php else : ?>
        <span><?php echo htmlentities(gettype($data)); ?>(<?php echo htmlentities(count($data)); ?>) {}</span>
    <?php endif; ?>
<?php else : ?>
    <div class="one-string-data">
        <?php switch(gettype($data)): ?><?php case 'integer': ?>int(<?php echo htmlentities(strlen($data)); ?>) <?php echo htmlentities($data); ?>
            <?php break; ?><?php case 'object': ?><?php echo htmlentities(var_dump($data)); ?>
            <?php break; ?><?php case 'boolean': ?>boolean(<?php echo htmlentities($data ? 'true' : 'false'); ?>)
            <?php break; ?>;
            <?php case 'NULL': ?><?php case 'null': ?>NULL
            <?php break; ?>;
            <?php default : ?>
            <?php echo htmlentities(gettype($data)); ?>(<?php echo htmlentities(strlen($data)); ?>) "<?php echo htmlentities($data); ?>"
        <?php endswitch; ?>
    </div>
<?php endif; ?>
