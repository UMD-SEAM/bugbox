<?php
/****************************************************************************
 ** file: core/templates/navbar.php
 **
 ** The top Store Locator Settings navigation bar.
 ***************************************************************************/
 
 global $slplus_plugin;
?>

<ul>
    <li class='like-a-button'><a href="<?php echo SLPLUS_ADMINPAGE;?>view-locations.php">Locations: Manage</a></li>
    <li class='like-a-button'><a href="<?php echo SLPLUS_ADMINPAGE;?>add-locations.php">Locations: Add</a></li>
    <li class='like-a-button'><a href="<?php echo SLPLUS_ADMINPAGE;?>map-designer.php">Settings: Map</a></li>
    <li class='like-a-button'><a href="<?php echo admin_url(); ?>options-general.php?page=csl-slplus-options">Settings: General</a></li>    
    <?php 
    //--------------------------------
    // Pro Version : Show Reports Tab
    //
    if ($slplus_plugin->license->packages['Pro Pack']->isenabled) {      
        print '<li class="like-a-button"><a href="'.SLPLUS_PLUSPAGE.'reporting.php">Reports</a></li>';
    }
    ?>    
</ul>


