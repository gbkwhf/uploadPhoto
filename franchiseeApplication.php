<!DOCTYPE html>
<html>
	<meta charset="utf-8">
	<title>申请加盟</title>
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="stylesheet" href="css/common.css">
	<link rel="stylesheet" href="css/franchiseeApplication.css">

	<body>
		<div class="essentialInformationBox">
			<div class="informationTitle">基本信息</div>
			<div class="inforName">
				<div class="lastName"><span class="symol">*</span>姓名：</div>
				<div class="inName"><input type="text" placeholder="请输入姓名" class="inputForName" /></div>
			</div>
			<div class="inforMobil">
				<div class="phoneNum"><span class="symol">*</span>电话：</div>
				<div class="inNum"><input type="tel" placeholder="请输入联系电话" class="inputForNum" maxlength="11" onkeyup="value=value.replace(/[^0-9.]/g,'') " /></div>
			</div>
			<div class="inforCompany">
				<div class="companys">公司名称：</div>
				<div class="firmName"><input type="text" placeholder="请输入公司名称" class="inputCompany" /></div>
			</div>
		</div>
		<div class="on_product">
			<div class="onProductTitle">产品信息</div>
			<div class="productBox">
				<div class="productName"><span class="symol">*</span>产品名称：</div>
				<div class="pros"><input type="text" placeholder="请输入产品名称" class="inputProductName" /></div>
			</div>
			<div class="proAttributesBox">
				<div class="attributesName">产品属性：</div>
				<div class="proAttributes"><input type="text" placeholder="请输入相关产品相关词汇" class="inputAttributes" /></div>
			</div>
			<div class="productDrawingBox">
				<div class="productDescription">产品配图<span class="remark">（1M以内，jpg，png格式）</span></div>
				<div class="productImg">
					<div id="uploadBox">
						<!--<div class="divImg" id="uploadImg">
						
					     </div>-->
					</div>

					<div class="uploadDIv">
						<span>+</span><input type="file" name="file" multiple id="inputs" accept="image/*" class='fileTest' multiple="multiple" />
					</div>
				</div>
			</div>
		</div>
		<div class="confirmApply">确定申请</div>
	</body>

</html>
<script src="js/jquery.min.js"></script>
<script src="js/layer/layer.js"></script>
<script src="js/common.js"></script>
<script src="js/config.js"></script>
<script type="text/javascript">
	//------------上传图片-------
	$(function() {
		var img = []; //创建一个空对象用来保存传入的图片
		var AllowImgFileSize = '1024'; //1兆
		$("#inputs").change(function() {
			var fil = this.files;
			for(var i = 0; i < fil.length; i++) {
				var curr = $('#inputs')[i].files[0].size;
				if(curr > AllowImgFileSize * 1024) { //当图片大于1兆提示
					layer.msg("图片文件大小超过限制 请上传小于1M的文件");
				} else {
					reads(fil[i]);
					img.push($('#inputs')[i].files[0]); //将传入的图片push到空对象中
				}
			}
			if(img.length >= 3) { //判断图片的数量，数量不能超过3张
				$('.uploadDIv').hide();
			} else {
				$('.uploadDIv').show();
			}
			console.log(img);
		});
		function reads(fil) {
			var reader = new FileReader();
			reader.readAsDataURL(fil);

			reader.onload = function() {
				document.getElementById("uploadBox").innerHTML += "<div class='divImg' id='uploadImg'><img src='" + reader.result + "' class='imgBiMG'></div>";
			}
		}
		$('.confirmApply').click(function() {
			//获取input框输入的值
			inputForName = $('.inputForName').val();
			inputForNum = $('.inputForNum').val();
			inputProductName = $('.inputProductName').val();
			inputCompany = $('.inputCompany').val();
			inputAttributes = $('.inputAttributes').val();
			console.log(inputForName + 'ddddd');
			console.log(inputForNum + 'ddddd');
			console.log(inputProductName + 'ddddd');
			console.log(inputCompany + 'ddddd');
			console.log(inputAttributes + 'ddddd');
			if(inputForName == "" || inputForName == undefined) { //对输入的值进行判断
				layer.msg("姓名不能为空");
			} else if(!testTel(inputForNum) || inputForNum == '' || inputForNum == undefined || inputForNum == null) {
				layer.msg("请输入正确的手机号码");
			} else if(inputProductName == "" || inputProductName == undefined) {
				layer.msg("产品名称不能为空");
			} else {
				var formData = new FormData(); //创建一个空的formData对象用来保存变量参数
				//				var arrhh= $('#inputs')[0].files[0]; //
				//				console.log(arrhh);
				//				img1 = $('.fileTest')[0].files[0];
				//				img2 = $('.fileTest')[0].files[0];
				//				img3 = $('.fileTest')[0].files[0];
				//              console.log(img[0]);
				formData.append("img_1", img[0]); //以键值对的形式将这些值保存到formData对象中
				formData.append("img_2", img[1]);
				formData.append("img_3", img[2]);
				formData.append("company_name", inputCompany);
				formData.append("goods_descript", inputAttributes);
				formData.append("goods_name", inputProductName);
				formData.append("mobile", inputForNum);
				formData.append("name", inputForName);
				layer.load(2);
				$.ajax({ //申请加盟
					type: "post", //请求方式
					dataType: 'json',
					url: commonsUrl + 'api/gxsc/joinsupplier' + versioninfos, //请求接口
					data: formData, //请求参数（这里将参数都保存在formData对象中）
					processData: false, //因为data值是FormData对象，不需要对数据做处理。
					contentType: false, //默认为true,不设置Content-type请求头
					success: function(data) {
						console.log(data)
						layer.closeAll();
						if(data.code == 1) { //请求成功
							//							layer.msg('上传成功');
							location.href = "subSuccess.php";
						} else {
							layer.msg(data.msg);
						}

					}
				});
			}
		})
	})
</script>