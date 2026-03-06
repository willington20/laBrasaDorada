<?php
// ================================================
// PANEL DE ADMINISTRACION - La Brasa Dorada
// CRUD completo: Platos + Reservas + Usuarios
// ================================================
require_once 'conexion.php';
$conn = conectar();

// --- Detectar seccion activa ---
$seccion = isset($_GET['s']) ? $_GET['s'] : 'dashboard';
$mensaje = '';

// ================================================
// CRUD PLATOS
// ================================================
if ($seccion === 'platos') {

    // CREAR / EDITAR plato
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_plato'])) {
        $nombre      = $conn->real_escape_string(trim($_POST['nombre']));
        $descripcion = $conn->real_escape_string(trim($_POST['descripcion']));
        $precio      = floatval($_POST['precio']);
        $categoria   = $conn->real_escape_string($_POST['categoria']);
        $disponible  = isset($_POST['disponible']) ? 1 : 0;

        if (isset($_POST['id']) && $_POST['id'] > 0) {
            // ACTUALIZAR
            $id = intval($_POST['id']);
            $conn->query("UPDATE platos SET nombre='$nombre', descripcion='$descripcion', precio=$precio, categoria='$categoria', disponible=$disponible WHERE id=$id");
            $mensaje = 'success|Plato actualizado correctamente';
        } else {
            // INSERTAR
            $conn->query("INSERT INTO platos (nombre, descripcion, precio, categoria, disponible) VALUES ('$nombre','$descripcion',$precio,'$categoria',$disponible)");
            $mensaje = 'success|Plato creado correctamente';
        }
    }

    // ELIMINAR plato
    if (isset($_GET['eliminar']) && $seccion === 'platos') {
        $id = intval($_GET['eliminar']);
        $conn->query("DELETE FROM platos WHERE id=$id");
        $mensaje = 'success|Plato eliminado';
    }

    // Obtener plato para editar
    $plato_editar = null;
    if (isset($_GET['editar'])) {
        $id = intval($_GET['editar']);
        $res = $conn->query("SELECT * FROM platos WHERE id=$id");
        $plato_editar = $res->fetch_assoc();
    }

    // Listar platos
    $platos = $conn->query("SELECT * FROM platos ORDER BY categoria, nombre");
}

// ================================================
// CRUD RESERVAS
// ================================================
if ($seccion === 'reservas') {

    // CAMBIAR ESTADO reserva
    if (isset($_GET['estado'])) {
        $id     = intval($_GET['id']);
        $estado = $conn->real_escape_string($_GET['estado']);
        $conn->query("UPDATE reservas SET estado='$estado' WHERE id=$id");
        $mensaje = 'success|Estado actualizado';
    }

    // ELIMINAR reserva
    if (isset($_GET['eliminar'])) {
        $id = intval($_GET['eliminar']);
        $conn->query("DELETE FROM reservas WHERE id=$id");
        $mensaje = 'success|Reserva eliminada';
    }

    // CREAR reserva manualmente
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_reserva'])) {
        $nombre   = $conn->real_escape_string(trim($_POST['nombre']));
        $telefono = $conn->real_escape_string(trim($_POST['telefono']));
        $fecha    = $conn->real_escape_string($_POST['fecha']);
        $hora     = $conn->real_escape_string($_POST['hora']);
        $personas = intval($_POST['personas']);
        $notas    = $conn->real_escape_string(trim($_POST['notas']));
        $conn->query("INSERT INTO reservas (nombre, telefono, fecha, hora, personas, notas) VALUES ('$nombre','$telefono','$fecha','$hora',$personas,'$notas')");
        $mensaje = 'success|Reserva creada correctamente';
    }

    // Filtro de estado
    $filtro = isset($_GET['filtro']) ? $conn->real_escape_string($_GET['filtro']) : 'todas';
    $where  = $filtro !== 'todas' ? "WHERE estado='$filtro'" : '';
    $reservas = $conn->query("SELECT * FROM reservas $where ORDER BY fecha DESC, hora DESC");
}

// ================================================
// CRUD USUARIOS
// ================================================
if ($seccion === 'usuarios') {

    // CREAR / EDITAR usuario
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_usuario'])) {
        $nombre = $conn->real_escape_string(trim($_POST['nombre']));
        $email  = $conn->real_escape_string(trim($_POST['email']));
        $rol    = $conn->real_escape_string($_POST['rol']);
        $activo = isset($_POST['activo']) ? 1 : 0;

        if (isset($_POST['id']) && $_POST['id'] > 0) {
            $id = intval($_POST['id']);
            // Solo cambiar password si se ingreso uno nuevo
            if (!empty($_POST['password'])) {
                $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $conn->query("UPDATE usuarios SET nombre='$nombre', email='$email', password='$pass', rol='$rol', activo=$activo WHERE id=$id");
            } else {
                $conn->query("UPDATE usuarios SET nombre='$nombre', email='$email', rol='$rol', activo=$activo WHERE id=$id");
            }
            $mensaje = 'success|Usuario actualizado';
        } else {
            $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $conn->query("INSERT INTO usuarios (nombre, email, password, rol, activo) VALUES ('$nombre','$email','$pass','$rol',$activo)");
            $mensaje = 'success|Usuario creado correctamente';
        }
    }

    // ELIMINAR usuario
    if (isset($_GET['eliminar'])) {
        $id = intval($_GET['eliminar']);
        $conn->query("DELETE FROM usuarios WHERE id=$id");
        $mensaje = 'success|Usuario eliminado';
    }

    // Obtener usuario para editar
    $usuario_editar = null;
    if (isset($_GET['editar'])) {
        $id = intval($_GET['editar']);
        $res = $conn->query("SELECT * FROM usuarios WHERE id=$id");
        $usuario_editar = $res->fetch_assoc();
    }

    $usuarios = $conn->query("SELECT * FROM usuarios ORDER BY nombre");
}

// ================================================
// DASHBOARD - Estadisticas
// ================================================
if ($seccion === 'dashboard') {
    $total_platos    = $conn->query("SELECT COUNT(*) as n FROM platos")->fetch_assoc()['n'];
    $total_reservas  = $conn->query("SELECT COUNT(*) as n FROM reservas")->fetch_assoc()['n'];
    $reservas_hoy    = $conn->query("SELECT COUNT(*) as n FROM reservas WHERE fecha=CURDATE()")->fetch_assoc()['n'];
    $total_usuarios  = $conn->query("SELECT COUNT(*) as n FROM usuarios")->fetch_assoc()['n'];
    $pendientes      = $conn->query("SELECT COUNT(*) as n FROM reservas WHERE estado='pendiente'")->fetch_assoc()['n'];
    $ultimas_reservas = $conn->query("SELECT * FROM reservas ORDER BY creado_en DESC LIMIT 5");
}

// Parsear mensaje
$msg_tipo = $msg_texto = '';
if ($mensaje) { list($msg_tipo, $msg_texto) = explode('|', $mensaje, 2); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin - La Brasa Dorada</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet"/>
  <style>
    *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
    :root{--gold:#c9a84c;--dark:#0e0b07;--dark2:#1a1409;--sidebar:#111;--white:#fff;--gray:#f5f5f5;--gray2:#e8e8e8;--text:#333;--muted:#888;--danger:#e74c3c;--success:#2ecc71;--warning:#f39c12}
    body{font-family:'Inter',sans-serif;background:var(--gray);color:var(--text);display:flex;min-height:100vh}

    /* SIDEBAR */
    .sidebar{width:240px;background:var(--sidebar);min-height:100vh;flex-shrink:0;display:flex;flex-direction:column}
    .sidebar-logo{padding:2rem 1.5rem;border-bottom:1px solid rgba(255,255,255,.08)}
    .sidebar-logo h1{font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--gold);letter-spacing:1px}
    .sidebar-logo p{font-size:.7rem;color:rgba(255,255,255,.3);margin-top:.2rem;letter-spacing:2px;text-transform:uppercase}
    .sidebar nav{flex:1;padding:1rem 0}
    .nav-item{display:flex;align-items:center;gap:.8rem;padding:.85rem 1.5rem;color:rgba(255,255,255,.5);text-decoration:none;font-size:.85rem;transition:all .2s;border-left:3px solid transparent}
    .nav-item:hover{background:rgba(255,255,255,.05);color:rgba(255,255,255,.8)}
    .nav-item.active{background:rgba(201,168,76,.1);color:var(--gold);border-left-color:var(--gold)}
    .nav-item .icon{font-size:1.1rem;width:20px;text-align:center}
    .sidebar-footer{padding:1.5rem;border-top:1px solid rgba(255,255,255,.08)}
    .sidebar-footer a{font-size:.75rem;color:rgba(255,255,255,.3);text-decoration:none;display:block;margin-bottom:.4rem}
    .sidebar-footer a:hover{color:var(--gold)}

    /* MAIN */
    .main{flex:1;display:flex;flex-direction:column;overflow:hidden}
    .topbar{background:var(--white);padding:1rem 2rem;border-bottom:1px solid var(--gray2);display:flex;justify-content:space-between;align-items:center}
    .topbar h2{font-size:1.1rem;font-weight:600;color:var(--text)}
    .topbar-right{display:flex;align-items:center;gap:.5rem;font-size:.85rem;color:var(--muted)}
    .content{flex:1;padding:2rem;overflow-y:auto}

    /* ALERTA */
    .alert{padding:.9rem 1.2rem;border-radius:4px;margin-bottom:1.5rem;font-size:.85rem;display:flex;align-items:center;gap:.5rem}
    .alert-success{background:#eafaf1;border:1px solid #a9dfbf;color:#1e8449}
    .alert-error{background:#fdedec;border:1px solid #f1948a;color:#c0392b}

    /* CARDS DASHBOARD */
    .cards-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.5rem;margin-bottom:2rem}
    .card{background:var(--white);border-radius:8px;padding:1.5rem;border:1px solid var(--gray2)}
    .card-num{font-size:2.2rem;font-weight:700;color:var(--dark);line-height:1}
    .card-label{font-size:.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-top:.4rem}
    .card-icon{font-size:1.8rem;margin-bottom:.8rem}
    .card.gold .card-num{color:var(--gold)}
    .card.danger .card-num{color:var(--danger)}

    /* TABLA */
    .table-wrap{background:var(--white);border-radius:8px;border:1px solid var(--gray2);overflow:hidden}
    .table-header{padding:1.2rem 1.5rem;border-bottom:1px solid var(--gray2);display:flex;justify-content:space-between;align-items:center}
    .table-header h3{font-size:.95rem;font-weight:600}
    table{width:100%;border-collapse:collapse}
    th{background:var(--gray);padding:.8rem 1rem;text-align:left;font-size:.75rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:1px}
    td{padding:.85rem 1rem;border-bottom:1px solid var(--gray2);font-size:.85rem;vertical-align:middle}
    tr:last-child td{border-bottom:none}
    tr:hover td{background:rgba(201,168,76,.03)}

    /* BADGES */
    .badge{display:inline-block;padding:.25rem .7rem;border-radius:20px;font-size:.7rem;font-weight:500;text-transform:uppercase;letter-spacing:.5px}
    .badge-success{background:#eafaf1;color:#1e8449}
    .badge-warning{background:#fef9e7;color:#9a7d0a}
    .badge-danger{background:#fdedec;color:#c0392b}
    .badge-info{background:#eaf4fb;color:#1a5276}
    .badge-gray{background:#f2f3f4;color:#555}

    /* BOTONES */
    .btn{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1rem;border:none;border-radius:4px;font-family:'Inter',sans-serif;font-size:.8rem;cursor:pointer;text-decoration:none;transition:all .2s;font-weight:500}
    .btn-primary{background:var(--gold);color:var(--dark)}
    .btn-primary:hover{background:#b8943e}
    .btn-sm{padding:.3rem .7rem;font-size:.75rem}
    .btn-edit{background:#eaf4fb;color:#1a5276}
    .btn-edit:hover{background:#d6eaf8}
    .btn-delete{background:#fdedec;color:#c0392b}
    .btn-delete:hover{background:#fadbd8}
    .btn-success{background:#eafaf1;color:#1e8449}
    .btn-success:hover{background:#d5f5e3}
    .btn-outline{background:transparent;border:1px solid var(--gray2);color:var(--text)}
    .btn-outline:hover{border-color:var(--gold);color:var(--gold)}

    /* FORMULARIO */
    .form-card{background:var(--white);border-radius:8px;border:1px solid var(--gray2);padding:1.5rem;margin-bottom:1.5rem}
    .form-card h3{font-size:.95rem;font-weight:600;margin-bottom:1.2rem;padding-bottom:.8rem;border-bottom:1px solid var(--gray2)}
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
    .form-group{margin-bottom:1rem}
    .form-group.full{grid-column:1/-1}
    label{display:block;font-size:.75rem;font-weight:500;color:var(--muted);margin-bottom:.4rem;text-transform:uppercase;letter-spacing:.5px}
    input[type=text],input[type=email],input[type=password],input[type=tel],input[type=date],input[type=time],input[type=number],select,textarea{
      width:100%;padding:.65rem .9rem;border:1px solid var(--gray2);border-radius:4px;font-family:'Inter',sans-serif;font-size:.85rem;transition:border .2s;outline:none;color:var(--text)
    }
    input:focus,select:focus,textarea:focus{border-color:var(--gold)}
    textarea{resize:none;height:80px}
    .form-check{display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.85rem}
    .form-check input{width:auto}
    .form-actions{display:flex;gap:.8rem;margin-top:1.2rem;padding-top:1rem;border-top:1px solid var(--gray2)}

    /* FILTROS */
    .filters{display:flex;gap:.5rem;margin-bottom:1rem;flex-wrap:wrap}
    .filter-btn{padding:.4rem .9rem;border:1px solid var(--gray2);background:var(--white);border-radius:20px;font-size:.75rem;cursor:pointer;text-decoration:none;color:var(--muted);transition:all .2s}
    .filter-btn:hover,.filter-btn.active{background:var(--gold);color:var(--dark);border-color:var(--gold)}

    @media(max-width:768px){
      .sidebar{display:none}
      .form-grid{grid-template-columns:1fr}
    }
  </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <h1>La Brasa Dorada</h1>
    <p>Panel Admin</p>
  </div>
  <nav>
    <a href="?s=dashboard" class="nav-item <?= $seccion==='dashboard'?'active':'' ?>">
      <span class="icon">&#128202;</span> Dashboard
    </a>
    <a href="?s=platos" class="nav-item <?= $seccion==='platos'?'active':'' ?>">
      <span class="icon">&#127869;</span> Platos
    </a>
    <a href="?s=reservas" class="nav-item <?= $seccion==='reservas'?'active':'' ?>">
      <span class="icon">&#128197;</span> Reservas
    </a>
    <a href="?s=usuarios" class="nav-item <?= $seccion==='usuarios'?'active':'' ?>">
      <span class="icon">&#128100;</span> Usuarios
    </a>
  </nav>
  <div class="sidebar-footer">
    <a href="../index.html">&#8592; Ver sitio web</a>
    <a href="#">&#128274; Cerrar sesion</a>
  </div>
</aside>

<!-- MAIN -->
<main class="main">
  <div class="topbar">
    <h2>
      <?php
        $titulos = ['dashboard'=>'Dashboard','platos'=>'Gestion de Platos','reservas'=>'Gestion de Reservas','usuarios'=>'Gestion de Usuarios'];
        echo $titulos[$seccion] ?? 'Admin';
      ?>
    </h2>
    <div class="topbar-right">&#128100; Administrador</div>
  </div>

  <div class="content">
    <?php if ($msg_texto): ?>
      <div class="alert alert-<?= $msg_tipo ?>">
        <?= $msg_tipo==='success' ? '&#9989;' : '&#9888;' ?> <?= htmlspecialchars($msg_texto) ?>
      </div>
    <?php endif; ?>

    <!-- ============================================
         DASHBOARD
    ============================================ -->
    <?php if ($seccion === 'dashboard'): ?>
      <div class="cards-grid">
        <div class="card gold">
          <div class="card-icon">&#127869;</div>
          <div class="card-num"><?= $total_platos ?></div>
          <div class="card-label">Platos en menu</div>
        </div>
        <div class="card">
          <div class="card-icon">&#128197;</div>
          <div class="card-num"><?= $total_reservas ?></div>
          <div class="card-label">Total reservas</div>
        </div>
        <div class="card gold">
          <div class="card-icon">&#8987;</div>
          <div class="card-num"><?= $reservas_hoy ?></div>
          <div class="card-label">Reservas hoy</div>
        </div>
        <div class="card danger">
          <div class="card-icon">&#128276;</div>
          <div class="card-num"><?= $pendientes ?></div>
          <div class="card-label">Pendientes</div>
        </div>
        <div class="card">
          <div class="card-icon">&#128100;</div>
          <div class="card-num"><?= $total_usuarios ?></div>
          <div class="card-label">Usuarios</div>
        </div>
      </div>

      <div class="table-wrap">
        <div class="table-header">
          <h3>&#128197; Ultimas Reservas</h3>
          <a href="?s=reservas" class="btn btn-outline btn-sm">Ver todas</a>
        </div>
        <table>
          <thead><tr><th>Nombre</th><th>Fecha</th><th>Hora</th><th>Personas</th><th>Estado</th></tr></thead>
          <tbody>
            <?php while ($r = $ultimas_reservas->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($r['nombre']) ?></td>
                <td><?= $r['fecha'] ?></td>
                <td><?= $r['hora'] ?></td>
                <td><?= $r['personas'] ?> pax</td>
                <td>
                  <?php
                    $b = ['pendiente'=>'badge-warning','confirmada'=>'badge-success','cancelada'=>'badge-danger'];
                    echo '<span class="badge '.$b[$r['estado']].'">'.$r['estado'].'</span>';
                  ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    <!-- ============================================
         PLATOS CRUD
    ============================================ -->
    <?php elseif ($seccion === 'platos'): ?>
      <div class="form-card">
        <h3><?= $plato_editar ? '&#9999; Editar Plato' : '&#10133; Nuevo Plato' ?></h3>
        <form method="POST">
          <?php if ($plato_editar): ?>
            <input type="hidden" name="id" value="<?= $plato_editar['id'] ?>"/>
          <?php endif; ?>
          <div class="form-grid">
            <div class="form-group">
              <label>Nombre del plato *</label>
              <input type="text" name="nombre" required placeholder="Ej: Lomo a la Brasa" value="<?= htmlspecialchars($plato_editar['nombre'] ?? '') ?>"/>
            </div>
            <div class="form-group">
              <label>Precio (COP) *</label>
              <input type="number" name="precio" required placeholder="65000" min="0" step="500" value="<?= $plato_editar['precio'] ?? '' ?>"/>
            </div>
            <div class="form-group">
              <label>Categoria *</label>
              <select name="categoria" required>
                <option value="">Selecciona...</option>
                <?php foreach (['entradas','principales','postres','bebidas'] as $cat): ?>
                  <option value="<?= $cat ?>" <?= ($plato_editar['categoria'] ?? '') === $cat ? 'selected' : '' ?>>
                    <?= ucfirst($cat) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group" style="display:flex;align-items:center;padding-top:1.5rem">
              <label class="form-check">
                <input type="checkbox" name="disponible" value="1" <?= ($plato_editar['disponible'] ?? 1) ? 'checked' : '' ?>/> Disponible
              </label>
            </div>
            <div class="form-group full">
              <label>Descripcion</label>
              <textarea name="descripcion" placeholder="Descripcion del plato..."><?= htmlspecialchars($plato_editar['descripcion'] ?? '') ?></textarea>
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" name="guardar_plato" class="btn btn-primary">
              <?= $plato_editar ? '&#9999; Actualizar' : '&#10133; Guardar Plato' ?>
            </button>
            <?php if ($plato_editar): ?>
              <a href="?s=platos" class="btn btn-outline">Cancelar</a>
            <?php endif; ?>
          </div>
        </form>
      </div>

      <div class="table-wrap">
        <div class="table-header"><h3>Lista de Platos (<?= $platos->num_rows ?>)</h3></div>
        <table>
          <thead><tr><th>#</th><th>Nombre</th><th>Categoria</th><th>Precio</th><th>Estado</th><th>Acciones</th></tr></thead>
          <tbody>
            <?php while ($p = $platos->fetch_assoc()): ?>
              <tr>
                <td><?= $p['id'] ?></td>
                <td><strong><?= htmlspecialchars($p['nombre']) ?></strong><br/><small style="color:var(--muted)"><?= htmlspecialchars(substr($p['descripcion'],0,50)) ?>...</small></td>
                <td><span class="badge badge-info"><?= $p['categoria'] ?></span></td>
                <td><strong>$<?= number_format($p['precio'],0,',','.') ?></strong></td>
                <td><span class="badge <?= $p['disponible'] ? 'badge-success' : 'badge-gray' ?>"><?= $p['disponible'] ? 'Disponible' : 'Oculto' ?></span></td>
                <td>
                  <a href="?s=platos&editar=<?= $p['id'] ?>" class="btn btn-edit btn-sm">&#9999; Editar</a>
                  <a href="?s=platos&eliminar=<?= $p['id'] ?>" class="btn btn-delete btn-sm" onclick="return confirm('Eliminar este plato?')">&#128465; Eliminar</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    <!-- ============================================
         RESERVAS CRUD
    ============================================ -->
    <?php elseif ($seccion === 'reservas'): ?>
      <div class="form-card">
        <h3>&#10133; Nueva Reserva Manual</h3>
        <form method="POST">
          <div class="form-grid">
            <div class="form-group">
              <label>Nombre *</label>
              <input type="text" name="nombre" required placeholder="Nombre del cliente"/>
            </div>
            <div class="form-group">
              <label>Telefono *</label>
              <input type="tel" name="telefono" required placeholder="+57 300..."/>
            </div>
            <div class="form-group">
              <label>Fecha *</label>
              <input type="date" name="fecha" required/>
            </div>
            <div class="form-group">
              <label>Hora *</label>
              <input type="time" name="hora" required/>
            </div>
            <div class="form-group">
              <label>Personas *</label>
              <input type="number" name="personas" required min="1" max="20" placeholder="2"/>
            </div>
            <div class="form-group full">
              <label>Notas</label>
              <textarea name="notas" placeholder="Observaciones..."></textarea>
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" name="guardar_reserva" class="btn btn-primary">&#10133; Crear Reserva</button>
          </div>
        </form>
      </div>

      <div class="filters">
        <?php foreach (['todas','pendiente','confirmada','cancelada'] as $f): ?>
          <a href="?s=reservas&filtro=<?= $f ?>" class="filter-btn <?= $filtro===$f?'active':'' ?>"><?= ucfirst($f) ?></a>
        <?php endforeach; ?>
      </div>

      <div class="table-wrap">
        <div class="table-header"><h3>Reservas (<?= $reservas->num_rows ?>)</h3></div>
        <table>
          <thead><tr><th>#</th><th>Cliente</th><th>Fecha</th><th>Hora</th><th>Pax</th><th>Estado</th><th>Acciones</th></tr></thead>
          <tbody>
            <?php while ($r = $reservas->fetch_assoc()): ?>
              <tr>
                <td><?= $r['id'] ?></td>
                <td>
                  <strong><?= htmlspecialchars($r['nombre']) ?></strong><br/>
                  <small style="color:var(--muted)"><?= $r['telefono'] ?></small>
                </td>
                <td><?= $r['fecha'] ?></td>
                <td><?= $r['hora'] ?></td>
                <td><?= $r['personas'] ?></td>
                <td>
                  <?php
                    $b = ['pendiente'=>'badge-warning','confirmada'=>'badge-success','cancelada'=>'badge-danger'];
                    echo '<span class="badge '.$b[$r['estado']].'">'.$r['estado'].'</span>';
                  ?>
                </td>
                <td style="display:flex;gap:.3rem;flex-wrap:wrap">
                  <?php if ($r['estado']==='pendiente'): ?>
                    <a href="?s=reservas&id=<?= $r['id'] ?>&estado=confirmada&filtro=<?= $filtro ?>" class="btn btn-success btn-sm">&#9989; Confirmar</a>
                    <a href="?s=reservas&id=<?= $r['id'] ?>&estado=cancelada&filtro=<?= $filtro ?>" class="btn btn-delete btn-sm">&#10060; Cancelar</a>
                  <?php endif; ?>
                  <a href="?s=reservas&eliminar=<?= $r['id'] ?>&filtro=<?= $filtro ?>" class="btn btn-delete btn-sm" onclick="return confirm('Eliminar reserva?')">&#128465;</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    <!-- ============================================
         USUARIOS CRUD
    ============================================ -->
    <?php elseif ($seccion === 'usuarios'): ?>
      <div class="form-card">
        <h3><?= $usuario_editar ? '&#9999; Editar Usuario' : '&#10133; Nuevo Usuario' ?></h3>
        <form method="POST">
          <?php if ($usuario_editar): ?>
            <input type="hidden" name="id" value="<?= $usuario_editar['id'] ?>"/>
          <?php endif; ?>
          <div class="form-grid">
            <div class="form-group">
              <label>Nombre *</label>
              <input type="text" name="nombre" required placeholder="Nombre completo" value="<?= htmlspecialchars($usuario_editar['nombre'] ?? '') ?>"/>
            </div>
            <div class="form-group">
              <label>Email *</label>
              <input type="email" name="email" required placeholder="correo@ejemplo.co" value="<?= htmlspecialchars($usuario_editar['email'] ?? '') ?>"/>
            </div>
            <div class="form-group">
              <label>Password <?= $usuario_editar ? '(dejar vacio para no cambiar)' : '*' ?></label>
              <input type="password" name="password" <?= $usuario_editar ? '' : 'required' ?> placeholder="Contrasena segura"/>
            </div>
            <div class="form-group">
              <label>Rol *</label>
              <select name="rol">
                <option value="admin"  <?= ($usuario_editar['rol'] ?? '') === 'admin'  ? 'selected' : '' ?>>Administrador</option>
                <option value="mesero" <?= ($usuario_editar['rol'] ?? '') === 'mesero' ? 'selected' : '' ?>>Mesero</option>
              </select>
            </div>
            <div class="form-group" style="display:flex;align-items:center;padding-top:1.5rem">
              <label class="form-check">
                <input type="checkbox" name="activo" value="1" <?= ($usuario_editar['activo'] ?? 1) ? 'checked' : '' ?>/> Usuario activo
              </label>
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" name="guardar_usuario" class="btn btn-primary">
              <?= $usuario_editar ? '&#9999; Actualizar' : '&#10133; Crear Usuario' ?>
            </button>
            <?php if ($usuario_editar): ?>
              <a href="?s=usuarios" class="btn btn-outline">Cancelar</a>
            <?php endif; ?>
          </div>
        </form>
      </div>

      <div class="table-wrap">
        <div class="table-header"><h3>Usuarios del sistema (<?= $usuarios->num_rows ?>)</h3></div>
        <table>
          <thead><tr><th>#</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr></thead>
          <tbody>
            <?php while ($u = $usuarios->fetch_assoc()): ?>
              <tr>
                <td><?= $u['id'] ?></td>
                <td><strong><?= htmlspecialchars($u['nombre']) ?></strong></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><span class="badge <?= $u['rol']==='admin' ? 'badge-warning' : 'badge-info' ?>"><?= $u['rol'] ?></span></td>
                <td><span class="badge <?= $u['activo'] ? 'badge-success' : 'badge-gray' ?>"><?= $u['activo'] ? 'Activo' : 'Inactivo' ?></span></td>
                <td>
                  <a href="?s=usuarios&editar=<?= $u['id'] ?>" class="btn btn-edit btn-sm">&#9999; Editar</a>
                  <a href="?s=usuarios&eliminar=<?= $u['id'] ?>" class="btn btn-delete btn-sm" onclick="return confirm('Eliminar usuario?')">&#128465; Eliminar</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</main>
</body>
</html>
