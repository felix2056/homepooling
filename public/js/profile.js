!function(e){function t(n){if(a[n])return a[n].exports;var r=a[n]={i:n,l:!1,exports:{}};return e[n].call(r.exports,r,r.exports,t),r.l=!0,r.exports}var a={};return t.m=e,t.c=a,t.i=function(e){return e},t.d=function(e,t,a){Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:a})},t.n=function(e){var a=e&&e.__esModule?function(){return e["default"]}:function(){return e};return t.d(a,"a",a),a},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="",t(t.s=0)}([function(e,t){function a(e){var t=$("#ver_num").attr("data-user"),a=$('meta[name="csrf-token"]').attr("content"),n={num:e};$.ajax({type:"POST",url:"/profiles/"+t+"/sms",data:n,context:document.body,headers:{"X-CSRF-TOKEN":a},beforeSend:function(){$(".phase_1 .alert")&&$(".phase_1 .alert").remove()},success:function(e){"ok"==e?($(".phase_1").removeClass("active"),$(".phase_2").addClass("active")):($(".phase_1 p").append('<div class="alert alert-danger" style="width:100%;margin-top:10px;">Something went wrong. Please, check the phone number and try again</div>'),setTimeout(function(){$(".alert.alert-danger").fadeOut(500,function(){$(".alert.alert-danger").remove()})},5e3))},error:function(e){}})}function n(e){var t=$("#ver_cod").attr("data-user"),a=$('meta[name="csrf-token"]').attr("content"),n={code:e};$.ajax({type:"POST",url:"/profiles/"+t+"/verify",data:n,context:document.body,headers:{"X-CSRF-TOKEN":a},beforeSend:function(){$(".phase_1 .alert")&&$(".phase_1 .alert").remove()},success:function(e){"ok"==e?($(".reveal_tooltip").remove(),$("#verify_popup").removeClass("active").remove(),$("#verify").unbind("click").addClass("disabled").addClass("verified").html('Verified<i class="fa fa-check"></i>'),$(".verify").append('<div class="alert alert-success" style="width:100%;margin-top:10px;">Your account has been successfully verified</div>'),setTimeout(function(){$(".alert.alert-success").fadeOut(500,function(){$(".alert.alert-success").remove()})},5e3)):"expired"==e?($(".phase_1").addClass("active"),$(".phase_1 p").append('<div class="alert alert-danger" style="width:100%;margin-top:10px;">Your code has expired. Please, try again (codes sent have a validity period of 1 hour)</div>'),$(".phase_2").removeClass("active"),setTimeout(function(){$(".alert.alert-danger").fadeOut(500,function(){$(".alert.alert-danger").remove()})},5e3)):($(".phase_1").addClass("active"),$(".phase_1 p").append('<div class="alert alert-danger" style="width:100%;margin-top:10px;">Something went wrong. Please, try again</div>'),$(".phase_2").removeClass("active"),setTimeout(function(){$(".alert.alert-danger").fadeOut(500,function(){$(".alert.alert-danger").remove()})},5e3))},error:function(e){}})}var r=new FormData;$(document).ready(function(){$("#reveal_tooltip").click(function(e){var t=$("#verify_tooltip");t.hasClass("nonVis")?(t.children("i").last().click(function(e){$("#verify_tooltip").addClass("nonVis"),$(this).unbind("click")}),t.removeClass("nonVis")):(t.children("i").last().unbind("click"),t.addClass("nonVis"))}),$("#verify").click(function(e){e.preventDefault(),$(".phase_1 button").click(function(e){e.preventDefault(),a($("#verify_number").val())}),$(".phase_2 button").click(function(e){e.preventDefault(),n($("#verify_code").val())}),$("#verify_popup .profile_card").click(function(e){e.stopPropagation()}),$("#verify").hasClass("verified")||($("#verify_popup").addClass("active"),$(".phase_1").addClass("active"),$("#verify_popup").click(function(e){$(this).removeClass("active"),$(this).unbind("click"),$(".phase_2 button").unbind("click"),$(".phase_1 button").unbind("click"),$("#verify_popup .profile_card").unbind("click")}))}),$("label[for='profile_photo']").click(function(){$("#profile_photo").change(function(){$($(this)[0].files).each(function(){var e=$(this);r=new FormData,r.append("photo",e[0]);var t=new FileReader;t.onload=function(e){var t=$(".profile_image img");t.attr("src",e.target.result)},t.readAsDataURL(e[0]),e.empty()})})})})}]);