<?php
$admin = new Ticketz_Tracker_Admin('', '');

// Adicionando URL
if (isset($_POST['add_url'])) {
    $admin->ticketz_tracker_add_url($_POST['url']);
}

// Atualizando URL
if (isset($_POST['update_url'])) {
    $admin->ticketz_tracker_update_url($_POST['id'], $_POST['url']);
}

// Deletando URL
if (isset($_POST['delete_url'])) {
    $admin->ticketz_tracker_delete_url($_POST['id']);
}
?>

<h2>Configurações do Ticketz Tracker</h2>
<form method="post" action="options.php">
    <?php
    settings_fields('ticketz-tracker');
    do_settings_sections('ticketz-tracker');
    ?>
    <label for="companies_id">ID da Empresa:</label>
    <input type="text" id="companies_id" name="companies_id" value="<?php echo get_option('companies_id'); ?>">
    <?php submit_button(); ?>
</form>

<h2>Gerenciador de URLs do Ticketz Tracker</h2>
<form method="post" action="">
    <input type="text" name="url" placeholder="Digite a URL">
    <input type="submit" name="add_url" value="Adicionar URL">
</form>

<h3>URLs Atuais</h3>
<table>
    <tr>
        <th>ID</th>
        <th>URL</th>
        <th>Ações</th>
    </tr>
    <?php
    $urls = $admin->ticketz_tracker_list_urls();
    foreach ($urls as $url) {
        ?>
        <tr>
            <td><?php echo $url->id; ?></td>
            <td><?php echo $url->url; ?></td>
            <td>
                <form method="post" action="">
                    <input type="hidden" name="id" value="<?php echo $url->id; ?>">
                    <input type="text" name="url" value="<?php echo $url->url; ?>">
                    <input type="submit" name="update_url" value="Atualizar">
                    <input type="submit" name="delete_url" value="Deletar">
                </form>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
