
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
<script src="/js/jquery-3.7.1.min.js"></script>
<script src="/js/datatables/dataTables.min.js"></script>
<script src="/js/datatables/dataTables.rowReorder.min.js"></script>
<script src="/js/datatables/dataTables.buttons.min.js"></script>
<script src="/js/datatables/dataTables.select.min.js"></script>
<script src="/js/datatables.default.js"></script>
<script src="/js/toastify.min.js"></script>
<script src="/js/summernote.min.js"></script>
