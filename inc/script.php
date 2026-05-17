<?php global $versione?>
<!--begin::Third Party Plugin(OverlayScrollbars)-->
<script
	src="/js/overlayscrollbars.browser.es6.min.js"
	crossorigin="anonymous"
></script>
<script
	src="/js/bootstrap.bundle.min.js"
	crossorigin="anonymous"
></script>
<!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
<script src="/js/adminlte.js"></script>
<!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
<script>
	const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
	const Default = {
		scrollbarTheme: 'os-theme-light',
		scrollbarAutoHide: 'leave',
		scrollbarClickScroll: true,
	};
	document.addEventListener('DOMContentLoaded', function () {
		const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
		if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
			OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
				scrollbars: {
					theme: Default.scrollbarTheme,
					autoHide: Default.scrollbarAutoHide,
					clickScroll: Default.scrollbarClickScroll,
				},
			});
		}
	});
</script>
<!--end::OverlayScrollbars Configure-->
<!-- OPTIONAL SCRIPTS -->
<script src="/js/jquery-3.7.1.min.js?v=<?php echo $versione; ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
<script src="/js/datatables/dataTables.min.js?v=<?php echo $versione; ?>"></script>
<script src="/js/datatables/dataTables.rowReorder.min.js?v=<?php echo $versione; ?>"></script>
<script src="/js/datatables/dataTables.buttons.min.js?v=<?php echo $versione; ?>"></script>
<script src="/js/datatables/dataTables.select.min.js?v=<?php echo $versione; ?>"></script>
<script src="/js/datatables/buttons.dataTables.js?v=<?php echo $versione; ?>"></script>
<script src="/js/datatables/buttons.html5.min.js?v=<?php echo $versione; ?>"></script>
<script src="/js/datatables.default.js?v=<?php echo $versione; ?>"></script>
<script src="/js/masonry.min.js?v=<?php echo $versione; ?>"></script>
<script src="/js/toastify.min.js?v=<?php echo $versione; ?>"></script>
<script src="/js/summernote-bs5.min.js?v=<?php echo $versione; ?>"></script>
<script src="/js/default.pages.js?v=<?php echo $versione; ?>"></script>
