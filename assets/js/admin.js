var ClickedContextMenuRole = 0;
jQuery(document).ready(function () {

    jQuery('html').click(function () {
        if (ClickedContextMenuRole == 1) {
            ClickedContextMenuRole = 0;
            return;
        }
        ClickedContextMenuRole = 0;
        var $RoleContextMenu = jQuery("#role-context-menu");
        $RoleContextMenu.hide();
    });


});

function RedContextMenuRoleobj(obj)
{
    window.location.href = obj.attr('data-href') + '&id=' + obj.attr('data-key');
}
function GetContextMenuRole(obj) {
    ClickedContextMenuRole = 1;
    var $RoleContextMenu = jQuery("#role-context-menu");
    $RoleContextMenu.css({
        display: "block",
        left: obj.offset().left + 80,
        top: obj.offset().top
    });
    jQuery("#role-context-menu a").attr('data-key', obj.attr('data-key'));
}