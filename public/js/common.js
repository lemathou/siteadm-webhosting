function action_popup(command)
{
window.open(command, 'action', config='height=100, width=400, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');
}

/* SERVICES */
function apache_reload()
{
action_popup('action.php?apache_reload');
}

function postfix_reload()
{
action_popup('action.php?postfix_reload');
}

