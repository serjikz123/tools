<div class="users form">
    <!--<form action="/xml2xls/upload" enctype="multipart/form-data" method="post">-->
    <?php echo $this->form->create('Xml2xls', ['type' => 'file', 'url' => 'xml2xls/upload']); ?>
        <fieldset>
            <legend><?= __('Select file') ?></legend>
            <?php echo $this->Form->input('Xml2xls.xmlfile',['type' => 'file']); ?>
       </fieldset>
        <?php echo $this->Form->button(__('Submit')); ?>
    <?php echo $this->form->end(); ?>
</div>