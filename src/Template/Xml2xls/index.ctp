<div class="users form">
    <!--<form action="/xml2xls/upload" enctype="multipart/form-data" method="post">-->
    <?php echo $this->Form->create('Xml2xls', ['type' => 'file', 'url' => 'xml2xls/upload']); ?>
        <fieldset>
            <legend><?= __('Select file') ?></legend>
            <?php echo $this->Form->input('Xml2xls.xmlfile',['type' => 'file', 'label' => false]); ?>
            <?php echo $this->Form->input('Xml2xls.itemkey',['type' => 'key', 'label' => __('Data search key')]); ?>
       </fieldset>
        <?php echo $this->Form->button(__('Submit')); ?>
    <?php echo $this->form->end(); ?>
</div>