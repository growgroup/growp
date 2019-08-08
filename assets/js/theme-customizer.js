;(function ($) {
	var register_lock = false;
	$(document).on("click", "#growp_view_sitemap", function (e) {
		console.log(e);
		e.preventDefault();
		$.ajax({
			type: "post",
			url: ajaxurl,
			data: {
				action: "growp_get_html_info",
				nonce: GROWP_THEMECUSTOMIZER.nonce
			}
		}).done(function (res) {
			console.log(res.data);
			var content = res.data;
			var _sitetree = res.data.sitetree;
			var sitetree = []
			for (var key in _sitetree) {
				sitetree.push(_sitetree[key])
			}
			sitetree = sitetree.sort(function (a, b) {
				if (a.relative_path === "/index.html") {
					return -1;
				}
				return 1;
			});
			var $sitetable = $("<table />", {class: "g-sitetree-table"});
			$sitetable.append($("<thead><tr><th>ID</th><th>タイトル</th><th>スラッグ</th><th>HTML</th></tr></thead>"));
			var $sitetable_tbody = $("<tbody />");

			for (var key in sitetree) {
				var page = sitetree[key];
				var $row = $("<tr />");
				$row.append($("<td />", {text: (key - 0) + 1})); // ID
				$row.append($("<td />", {text: page.title})); // ページタイトル
				$row.append($("<td />", {text: page.relative_path.replace("index.html", "")})); // スラッグ
				var $pre = $("<textarea />", {style: "height: 100vw"}).html(page.main_content_html);
				var $button = $("<button />", {text: "htmlを見る", class: "show_html_code"}).attr("data-site-key", key);
				var $page_create_button = $("<button />", {
					text: "固定ページを作成",
					class: "craete_page"
				}).attr("data-site-key", key);
				$button.on("click", function (e) {
					e.preventDefault();
					new jBox('Modal', {
						title: "HTML",
						content: $pre,
						zIndex: 1000000200,
						color: 'black',
						width: "100vw",
						height: "100vh",
					}).open();
					var editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
					editorSettings.codemirror = _.extend(
						{},
						editorSettings.codemirror,
						{
							mode: 'html'
						},
					);
					wp.codeEditor.initialize($pre);
				});
				$page_create_button.on("click", function (e) {
					e.preventDefault();
					var $content = $("<div />", {class: "g-createpage-form"});
					var $pagename = $("<input />", {
						type: "text",
						name: "post_title",
						value: page.title
					});

					var $pageslug = $("<input />", {
						type: "text",
						name: "post_name",
						value: page.relative_path.replace("index.html", "").slice(1,-1).replace(/\//g, "_")
					});
					var $pagecontent = $("<input />", {
						name: "post_content",
						type: "checkbox",
						value: "HTMLを一緒に保存する"
					});
					var $row = $("<div />", {class: "g-createpage-form__row"});
					$row.append("<label>ページタイトル</label><br>");
					$row.append($pagename);
					$content.append($row);
					var $row = $("<div />", {class: "g-createpage-form__row"});
					$row.append("<label>ページのスラッグ</label><br>");
					$row.append($pageslug);
					$content.append($row);
					var $row = $("<div />", {class: "g-createpage-form__row"});
					$row.append("<label>ページのHTMLを一緒に保存する</label><br>");
					$row.append($pagecontent);
					$content.append($row);
					var $submitbutton = $("<button />", {
						class: "g-createpage-submit button-primary",
						text: "この情報で固定ページを作成"
					});
					$content.append($submitbutton);
					new jBox("Modal", {
						title: "ページを作成",
						content: $content,
						zIndex: 100000000,
					}).open();
					$submitbutton.on("click", function (e) {
						e.preventDefault();
						$.ajax({
							type: "post",
							url: ajaxurl,
							data: {
								action: "growp_create_page",
								nonce: GROWP_THEMECUSTOMIZER.nonce
							}
						}).done(function (res) {
							console.log(res);
						})
					})

				});
				$row.append($("<td />").append($button).append($page_create_button)); // スラッグ
				$sitetable_tbody.append($row)
			}
			$sitetable.append($sitetable_tbody);
			content = "";
			new jBox('Modal', {
				title: "HTML情報",
				content: $sitetable,
				zIndex: 10000000,
				color: 'black',
			}).open();
		})
	});
	$(document).on("click", "#growp_register_components", function (e) {
		e.preventDefault();
		if (register_lock) {
			return false;
		}
		register_lock = true;
		var $button = $(this);
		var $spinner = $button.find(".spinner").addClass("is-active");
		$.ajax({
			type: "post",
			url: ajaxurl,
			data: {
				action: "growp_register_components",
				nonce: GROWP_THEMECUSTOMIZER.nonce
			}
		}).done(function (res) {
			$spinner.removeClass("is-active");
			new jBox('Notice', {
				content: 'コンポーネントの登録に成功しました',
				zIndex: 10000000,
				color: 'black',
			}).open();
			register_lock = false;
		})
	})

})(jQuery);
