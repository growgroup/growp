(function ($) {
	$(function () {
		$("#wp-admin-bar-devnote").attr("data-target", "note_list");
		$('.collapsible').collapsible();
		var $new_note_modal = $('#new_note').modal();
		var $note_list_modal = $('#note_list').modal();
		function sort_init() {
			var sortel = document.getElementById('note_items');
			if ($.isEmptyObject(sortel)) {
				return "";
			}
			var sortable = Sortable.create(sortel, {
				group: "notestorage",
				sort: true,
				delayOnTouchOnly: true,
				dataIdAttr: 'data-id',
				onUpdate: function (evt) {
					var $items = $(sortel).find(".note-item");
					var indexes = [];
					for (var i = 0; i < $items.length; i++) {
						var $item = $($items[i]);
						indexes.push({
							post_id: $item.data("id"),
							menu_order: i,
						});
					}
					$.ajax({
						type: "post",
						url: "/update_sort_notes/",
						data: {
							indexes: indexes
						}
					}).done(function (res) {
						console.log(res);
						fetch_notes()
					})
				},
			});
		}

		sort_init();

		function fetch_notes() {
			$.ajax({
				type: "get",
				url: "/get_notes/",
			}).done(function (res) {
				$target.html(res.data.html);
				$('.collapsible').collapsible();
				sort_init();
			})
		}

		// 編集
		$(document).on("click", ".edit_note_submit", function (e) {
			e.preventDefault();
			var post_id = $(this).data("post-id");
			var content = $(this).closest(".note-item").find(".js-note-text").html().trim();
			var post_title = $(this).closest(".note-item").find(".js-note-title").text().trim()
			var $modal = $("#edit_note");
			$modal.modal();
			$modal.modal("open");
			$modal.find("#edit_note_title").val(post_title);
			$modal.find("#edit_note_content").val(content);
			$modal.find("#edit_note_content").html(content);
			$modal.find("#edit_post_id").val(post_id);
			M.textareaAutoResize($modal.find("#edit_note_content"))
		});
		// 編集内容の保存
		$("#edit_note_form").on("submit", function (e) {
			e.preventDefault();
			var new_content = $("#edit_note_content").val().trim();
			var new_title = $("#edit_note_title").val().trim();
			var post_id = $("#edit_post_id").val();
			if (!new_content.trim() || !post_id) {
				alert("内容が入力されていません。");
				return false;
			}
			$.ajax({
				type: "POST",
				url: "/edit_note/",
				data: {
					post_title: new_title,
					post_content: new_content,
					post_id: post_id,
				}
			}).done(function (res) {
				// alert(res.data.message);
				$("#edit_note_title").val("");
				$("#edit_note_content").html("");
				fetch_notes();
				$('#edit_note').modal("close");
			}).fail(function () {
				alert("エラーが発生しました");
			})
		});
		// 追加
		$("#new_note_form").on("submit", function (e) {

			e.preventDefault();
			var new_content = $("#new_note_content").val().trim();
			var new_title = $("#new_note_title").val().trim();
			if (!new_title.trim()) {
				alert("タイトルは必須です");
				return false;
			}
			if (!new_content.trim()) {
				alert("内容が入力されていません。");
				return false;
			}
			$.ajax({
				type: "POST",
				url: "/add_note/",
				data: {
					post_content: new_content,
					post_title: new_title
				}
			}).done(function (res) {
				// alert(res.data.message);
				$("#new_note_title").val("");
				$("#new_note_content").html("");
				$("#new_note_content").val("");
				fetch_notes();
				$('#new_note').modal("close");
			}).fail(function () {
				alert("エラーが発生しました");
			})
		});
		var $target = $("#notelistrender");

		// 削除
		$(document).on("click", ".delete_note_submit", function (e) {
			e.preventDefault();
			var $parent_form = $(this).closest("form");
			var post_id = $parent_form.find("input[name=post_id]").val();
			if (!confirm("本当に削除しますか？")) {
				return false;
			}
			if (!post_id) {
				alert("エラーが起きました");
				return false;
			}
			$.ajax({
				type: "POST",
				url: "/delete_note/",
				data: {
					post_id: post_id
				}
			}).done(function (res) {
				fetch_notes()
			}).fail(function () {
				alert("エラーが発生しました");
			})
		});
	});
})(jQuery);
