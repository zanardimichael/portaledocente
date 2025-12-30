<?php
	global $pages;
	global $page;
?>
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
	<!--begin::Sidebar Brand-->
	<div class="sidebar-brand">
		<!--begin::Brand Link-->
		<a href="/" class="brand-link">
			<!--begin::Brand Text-->
			<span class="brand-text fw-light ms-0">Portale Docente</span>
			<!--end::Brand Text-->
		</a>
		<!--end::Brand Link-->
	</div>
	<!--end::Sidebar Brand-->
	<!--begin::Sidebar Wrapper-->
	<div class="sidebar-wrapper">
		<nav class="mt-2">
			<!--begin::Sidebar Menu-->
			<ul
				class="nav sidebar-menu flex-column"
				data-lte-toggle="treeview"
				role="navigation"
				aria-label="Main navigation"
				data-accordion="false"
				id="navigation"
			>
				<?php

					foreach ($pages as $curr => $conf){
                        if(count(explode("/", $curr)) > 1) continue;
						if($conf["separator"]){
							echo "<li class=\"nav-header\">".strtoupper($conf["title"])."</li>";
						}else{
						?>
						<li class="nav-item">
							<a href="<?php echo $conf["url"] ?>" class="nav-link <?php if ($page->current_page == $curr){ echo "active"; } ?>">
								<i class="nav-icon <?php echo $conf["icon"] ?>"></i>
								<p><?php echo $conf["title"] ?></p>
							</a>
						</li>
						<?php
						}
					}
				?>
			</ul>
			<!--end::Sidebar Menu-->
		</nav>
	</div>
	<!--end::Sidebar Wrapper-->
</aside>