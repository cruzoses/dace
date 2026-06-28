<style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 8pt;
    }
    th {
        background-color: #003366;
        color: #ffffff;
        padding: 6px 4px;
        font-weight: bold;
        text-align: center;
    }
    td {
        padding: 4px;
        border-bottom: 1px solid #cccccc;
    }
    tr:nth-child(even) {
        background-color: #f5f5f5;
    }
</style>

<table>
    <thead>
        <tr>
            <th width="60">Codigo</th>
            <th>Nombre del Rol</th>
            <th width="80">Estatus</th>
            <th width="120">Creado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rols as $rol): ?>
        <tr>
            <td align="center"><?= $rol->id ?></td>
            <td><?= h($rol->nombre) ?></td>
            <td align="center"><?= $rol->activo ? 'Activo' : 'Inactivo' ?></td>
            <td align="center"><?= $rol->created->format('d/m/Y') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
