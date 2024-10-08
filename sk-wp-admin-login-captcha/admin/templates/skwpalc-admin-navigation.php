<h2 class="nav-tab-wrapper">
    <?php
    foreach ($template_data['settings_sections'] as $tab) {
        echo sprintf('<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title']);
    }
    ?>
</h2>