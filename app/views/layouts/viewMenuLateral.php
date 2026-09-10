<?php
$currentUrl = $_REQUEST['url'] ?? '';
$pedidoOpen = in_array($currentUrl, ['pedido', 'cliente'], true);
$productoOpen = in_array($currentUrl, ['producto', 'categoria'], true);
$configOpen = in_array($currentUrl, ['user', 'rol', 'metodopago', 'moneda', 'iva', 'medida', 'empresa'], true);

function navItemClass(string $url, string $current): string {
    $base = 'group flex items-center gap-3 mx-2 my-1 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 ease-in-out border-l-4';
    if ($url === $current) {
        return $base . ' bg-slate-800/80 text-white border-orange-500';
    }
    return $base . ' text-slate-400 border-transparent hover:bg-slate-800 hover:text-white';
}

function navToggleClass(bool $isOpen, bool $isActive): string {
    $base = 'dropdown-toggle group flex w-[calc(100%-1rem)] items-center gap-3 mx-2 my-1 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 ease-in-out border-l-4 text-left';
    if ($isActive || $isOpen) {
        return $base . ' bg-slate-800/80 text-white border-orange-500';
    }
    return $base . ' text-slate-400 border-transparent hover:bg-slate-800 hover:text-white';
}

function navSubClass(string $url, string $current): string {
    $base = 'block pl-10 pr-4 py-2.5 text-sm font-medium transition-all duration-200 ease-in-out';
    if ($url === $current) {
        return $base . ' text-white bg-slate-800/60';
    }
    return $base . ' text-slate-500 hover:text-white hover:bg-slate-800/40';
}

function navIconClass(bool $active): string {
    return 'w-5 h-5 shrink-0 transition-colors duration-200 ' . ($active ? 'text-orange-500' : 'text-slate-400 group-hover:text-slate-200');
}
?>
<aside class="sidebar-scroll fixed inset-y-0 left-0 z-[100] flex w-64 min-h-screen shrink-0 flex-col overflow-y-auto bg-slate-900 shadow-2xl">
  <header class="border-b border-white/10 px-5 py-6">
    <div class="flex items-center gap-3">
      <div class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-full">
        <img src="assets/img/logo.jpeg" alt="Logo CopiGaby" class="h-full w-full rounded-full object-cover">
      </div>
      <div>
        <p class="text-[0.65rem] font-bold uppercase tracking-[0.2em] text-slate-500">Sistema</p>
        <h1 class="text-base font-semibold leading-tight text-white">
          Copi<span class="text-orange-500">Gaby</span>
          <span class="text-orange-500">2023</span>
        </h1>
      </div>
    </div>
  </header>

  <p class="px-5 pb-2 pt-5 text-[0.65rem] font-bold uppercase tracking-[0.18em] text-slate-600">Menú principal</p>

  <nav class="flex flex-1 flex-col pb-4">
    <a href="?url=dashboard" class="<?= navItemClass('dashboard', $currentUrl) ?>">
      <svg class="<?= navIconClass($currentUrl === 'dashboard') ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
      </svg>
      Inicio
    </a>

    <div class="dropdown-parent<?= $pedidoOpen ? ' open' : '' ?>">
      <button type="button" class="<?= navToggleClass($pedidoOpen, in_array($currentUrl, ['pedido', 'cliente'], true)) ?>">
        <svg class="<?= navIconClass($pedidoOpen) ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <span class="flex-1">Pedido</span>
        <svg class="nav-chevron ml-auto h-4 w-4 shrink-0 text-slate-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <div class="dropdown-menu flex flex-col bg-slate-950/50">
        <a href="?url=pedido" class="<?= navSubClass('pedido', $currentUrl) ?>">Pedidos</a>
        <a href="?url=cliente" class="<?= navSubClass('cliente', $currentUrl) ?>">Clientes</a>
      </div>
    </div>

    <a href="?url=compra" class="<?= navItemClass('compra', $currentUrl) ?>">
      <svg class="<?= navIconClass($currentUrl === 'compra') ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
      </svg>
      Compras
    </a>

    <div class="dropdown-parent<?= $productoOpen ? ' open' : '' ?>">
      <button type="button" class="<?= navToggleClass($productoOpen, in_array($currentUrl, ['producto', 'categoria'], true)) ?>">
        <svg class="<?= navIconClass($productoOpen) ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
        <span class="flex-1">Producto</span>
        <svg class="nav-chevron ml-auto h-4 w-4 shrink-0 text-slate-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <div class="dropdown-menu flex flex-col bg-slate-950/50">
        <a href="?url=producto" class="<?= navSubClass('producto', $currentUrl) ?>">Productos-Insumo</a>
        <a href="?url=categoria" class="<?= navSubClass('categoria', $currentUrl) ?>">Categoría</a>
      </div>
    </div>

    <a href="?url=servicio" class="<?= navItemClass('servicio', $currentUrl) ?>">
      <svg class="<?= navIconClass($currentUrl === 'servicio') ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
      </svg>
      Servicios
    </a>

    <a href="?url=proveedor" class="<?= navItemClass('proveedor', $currentUrl) ?>">
      <svg class="<?= navIconClass($currentUrl === 'proveedor') ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
      </svg>
      Proveedores
    </a>

    <div class="dropdown-parent<?= $configOpen ? ' open' : '' ?>">
      <button type="button" class="<?= navToggleClass($configOpen, $configOpen) ?>">
        <svg class="<?= navIconClass($configOpen) ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
        </svg>
        <span class="flex-1">Configuración</span>
        <svg class="nav-chevron ml-auto h-4 w-4 shrink-0 text-slate-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <div class="dropdown-menu flex flex-col bg-slate-950/50">
        <a href="?url=rol" class="<?= navSubClass('rol', $currentUrl) ?>">Rol</a>
        <a href="?url=metodopago" class="<?= navSubClass('metodopago', $currentUrl) ?>">Método de Pago</a>
        <a href="?url=moneda" class="<?= navSubClass('moneda', $currentUrl) ?>">Moneda</a>
        <a href="?url=iva" class="<?= navSubClass('iva', $currentUrl) ?>">IVA</a>
        <a href="?url=empresa" class="<?= navSubClass('empresa', $currentUrl) ?>">Empresa</a>
        <a href="?url=medida" class="<?= navSubClass('medida', $currentUrl) ?>">Unidad de Medida</a>
        <a href="?url=user" class="<?= navSubClass('user', $currentUrl) ?>">Usuarios</a>
      </div>
    </div>

    <a href="?url=reporte" class="<?= navItemClass('reporte', $currentUrl) ?>">
      <svg class="<?= navIconClass($currentUrl === 'reporte') ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
      </svg>
      Reporte
    </a>
  </nav>

  <footer class="mt-auto border-t border-white/10 p-4">
    <a href="?url=login" class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-500 transition-all duration-200 ease-in-out hover:bg-red-500/10 hover:text-red-300">
      <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
      </svg>
      Cerrar sesión
    </a>
  </footer>
</aside>

<script>
(function () {
  document.querySelectorAll('.dropdown-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var parent = btn.closest('.dropdown-parent');
      var willOpen = !parent.classList.contains('open');
      document.querySelectorAll('.dropdown-parent').forEach(function (el) {
        el.classList.remove('open');
      });
      if (willOpen) parent.classList.add('open');
    });
  });

  document.querySelectorAll('form[method="POST"]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      if (form.dataset.submitting === '1') {
        e.preventDefault();
        return;
      }
      form.dataset.submitting = '1';
      var btn = form.querySelector('button[type="submit"]');
      if (btn) {
        btn.disabled = true;
        btn.classList.add('opacity-60', 'cursor-not-allowed');
      }
    });
  });
})();
</script>
