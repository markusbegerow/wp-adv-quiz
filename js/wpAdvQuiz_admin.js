jQuery(document).ready(function ($) {
    /**
     * @memberOf $.fn
     */
    $.fn.wpAdvQuiz_preview = function () {
        var methods = {
            openPreview: function (obj) {
                window.open($(obj).attr('href'), 'wpAdvQuizPreview', 'width=900,height=900');
            }
        };

        var init = function () {
            $('.wpAdvQuiz_prview').click(function (e) {
                methods.openPreview(this);
                e.preventDefault();
            });
        };

        init();
    };

    $.fn.wpAdvQuiz_quizOverall = function () {

        //function isEmpty(text) {
        //	text = $.trim(text);
        //
        //	return (!text || 0 === text.length);
        //};
        //
        //function ajaxPost(func, data, success) {
        //	var d = {
        //		action: 'wp_adv_quiz_admin_ajax',
        //		func: func,
        //		data: data
        //	};
        //
        //	$.post(ajaxurl, d, success, 'json');
        //};
        //
        //var methods = {
        //	changeExport: function(input) {
        //		$input = $(input);
        //		$export = $('.wpAdvQuiz_exportList');
        //		$export2 = $('.wpAdvQuiz_setQuizCategoryList');
        //		$ul = $export.find('ul').first();
        //		$ul2 = $export2.find('ul').first();
        //		$export.find('li').remove();
        //		$export2.find('li').remove();
        //
        //		$('input[name="exportItems"]').each(function() {
        //			$this = $(this);
        //
        //			if(this.checked) {
        //				var text = $this.parent().parent().find('.wpAdvQuiz_quizName a:eq(0)').text();
        //				$('<li>' + text + '</li>').appendTo($ul);
        //				$('<li>' + text + '</li>').appendTo($ul2);
        //			}
        //		});
        //	},
        //
        //	startExport: function() {
        //		$ele = $('input[name="exportItems"]:checked');
        //
        //		if($ele.length < 1) {
        //			alert(wpAdvQuizLocalize.no_selected_quiz);
        //			return false;
        //		}
        //
        //		$hidden = $('#exportHidden');
        //
        //		$hidden.html('');
        //
        //		$('input[name="exportItems"]').each(function() {
        //			$this = $(this);
        //
        //			if(this.checked) {
        //				$('<input type="hidden" value="'+ this.value +'" name="exportIds[]">').appendTo($hidden);
        //			}
        //		});
        //
        //		return true;
        //	},
        //
        //	setCategoriesStart: function() {
        //		$ele = $('input[name="exportItems"]:checked');
        //
        //		if($ele.length < 1) {
        //			alert(wpAdvQuizLocalize.no_selected_quiz);
        //			return false;
        //		}
        //
        //		var ids = [];
        //
        //		$('input[name="exportItems"]').each(function() {
        //			$this = $(this);
        //
        //			if(this.checked) {
        //				ids.push(this.value);
        //			}
        //		});
        //
        //		var categoryId = $('select[name="category"]').val();
        //
        //		var data = {
        //			categoryId: categoryId,
        //			quizIds: ids
        //		};
        //
        //		$('#ajaxLoad').show();
        //
        //		ajaxPost('setQuizMultipleCategories', data, function(json) {
        //			location.reload();
        //		});
        //
        //		return true;
        //	},
        //
        //	addCategory: function() {
        //		var name = $.trim($('input[name="categoryAdd"]').val());
        //
        //		if(isEmpty(name)) {
        //			return;
        //		}
        //
        //		var data = {
        //			categoryName: name,
        //			type: 'quiz'
        //		};
        //
        //		ajaxPost('categoryAdd', data, function(json) {
        //			if(json.err) {
        //				$('#categoryMsgBox').text(json.err).show('fast').delay(2000).hide('fast');
        //				return;
        //			}
        //
        //			var $option = $(document.createElement('option'))
        //				.val(json.categoryId)
        //				.text(json.categoryName)
        //				.attr('selected', 'selected');
        //
        //			$('select[name="category"]').append($option).change();
        //
        //		});
        //	}
        //};
        //
        //var init = function() {
        //	$('.wpAdvQuiz_delete').click(function(e) {
        //		var b = confirm(wpAdvQuizLocalize.delete_msg);
        //
        //		if(!b) {
        //			e.preventDefault();
        //			return false;
        //		}
        //
        //		return true;
        //	});
        //
        //	$('.wpAdvQuiz_import').click(function(e) {
        //		e.preventDefault();
        //		$('.wpAdvQuiz_importList').show('fast');
        //
        //		$('.wpAdvQuiz_exportList, .wpAdvQuiz_setQuizCategoryList').hide();
        //		$('.wpAdvQuiz_exportCheck').hide();
        //
        //	});
        //
        //	$('.wpAdvQuiz_export').click(function(e) {
        //		e.preventDefault();
        //
        //		$('.wpAdvQuiz_exportList').show('fast');
        //		$('.wpAdvQuiz_exportCheck').show('fast');
        //		$('.wpAdvQuiz_importList, .wpAdvQuiz_setQuizCategoryList').hide();
        //	});
        //
        //	$('.wpAdvQuiz_setQuizCategory').click(function(e) {
        //		e.preventDefault();
        //
        //		$('.wpAdvQuiz_setQuizCategoryList').show('fast');
        //		$('.wpAdvQuiz_exportCheck').show('fast');
        //		$('.wpAdvQuiz_importList, .wpAdvQuiz_exportList').hide();
        //	});
        //
        //	$('input[name="exportItems"]').change(function() {
        //		methods.changeExport(this);
        //	});
        //
        //	$('input[name="exportItemsAll"]').change(function() {
        //		var $input = $('input[name="exportItems"]');
        //		if(this.checked)
        //			$input.attr('checked', true);
        //		else
        //			$input.attr('checked', false);
        //
        //		$input.change();
        //	});
        //
        //	$('#exportStart').click(function(e) {
        //
        //		if(!methods.startExport())
        //			e.preventDefault();
        //	});
        //
        //	$('#setCategoriesStart').click(function(e) {
        //
        //		if(!methods.setCategoriesStart())
        //			e.preventDefault();
        //	});
        //
        //	$('select[name="category"]').change(function() {
        //		var $this = $(this);
        //		var box = $('#categoryAddBox').hide();
        //
        //
        //		if($this.val() == "-1") {
        //			box.show();
        //		}
        //
        //	}).change();
        //
        //	$('#categoryAddBtn').click(function() {
        //		methods.addCategory();
        //	});
        //};
        //
        //init();
    };

//	$.fn.wpAdvQuiz_questionEdit = function() {
//
//		var setup = function() {
//			if($('input[name="answerType"][checked="checked"]').size() < 1) {
//				$('input[name="answerType"][value="single"]').attr({'checked': 'checked'});
//			}
//
//			$('input[name="answerType"]:checked').click();
//			$('#wpAdvQuiz_correctSameText').change();
//			$('#wpAdvQuiz_tip').change();
//			$('input[name="pointsPerAnswer"]').change();
//			$('input[name="answerPointsActivated"]').change();
//		};
//
//		var formListener = {
//			setup: function() {
//				$('input[name="answerType"]').click(function(e) {
//					$('.answer_felder').children().css('display', 'none');
//
//					switch (this.value) {
//					case 'single':
//						formListener.displaySingle('radio');
//						break;
//					case 'multiple':
//						formListener.displaySingle('checkbox');
//						break;
//					case 'free_answer':
//						formListener.displayFreeAnswer();
//						break;
//					case 'sort_answer':
//						formListener.displaySortAnswer();
//						break;
//					case 'matrix_sort_answer':
//						formListener.displayMatrixSortAnswer();
//						break;
//					case 'cloze_answer':
//						formListener.displayClozeAnswer();
//						break;
//					}
//				});
//
//				$('.addAnswer').click(function(e) {
//					formListener.addAnswer(this);
//				});
//
//				$('.deleteAnswer').click(function(e) {
//					formListener.deleteAnswer(this);
//				});
//
//				$('#saveQuestion').click(function(e) {
//					return validate();
//				});
//
//				$('.sort_answer ul, .classic_answer ul, .matrix_sort_answer ul').sortable({
//					handle: '.wpAdvQuiz_move',
//					update: function(event, ui) {
//						formListener.setValueClassicAnswer();
//					}
//				});
//
//				$('#wpAdvQuiz_correctSameText').change(function() {
//					if(this.checked)
//						$('#wpAdvQuiz_incorrectMassageBox').hide();
//					else
//						$('#wpAdvQuiz_incorrectMassageBox').show();
//				});
//
//				$('#wpAdvQuiz_tip').change(function(e) {
//					if(this.checked)
//						$('#wpAdvQuiz_tipBox').show();
//					else
//						$('#wpAdvQuiz_tipBox').hide();
//				});
//
//				$('input[name="pointsPerAnswer"]').change(function() {
//					if(this.checked) {
//						$('#wpAdvQuiz_showPointsBox').show();
//					} else {
//						$('#wpAdvQuiz_showPointsBox').hide();
//					}
//				});
//
//				$('input[name="answerPointsActivated"]').change(function() {
//					if(this.checked) {
//						$('input[name="points"]').attr('disabled', 'disabled');
//						$('.wpAdvQuiz_answerPoints').show();
//						$('#wpAdvQuiz_showPointsBox').show();
//					} else {
//						$('input[name="points"]').removeAttr('disabled');
//						$('.wpAdvQuiz_answerPoints').hide();
//						$('#wpAdvQuiz_showPointsBox').hide();
//					}
//				});
//
//				$('.wpAdvQuiz_demoBox a').mouseover(function() {
//					$(this).next().show();
//				}).mouseout(function() {
//					$(this).next().hide();
//				}).click(function() {
//					return false;
//				});
//
//			},
//
//			displaySingle: function(type) {
//				$('.classic_answer').find('input[name="answerJson[classic_answer][correct][]"]').each(function() {
//					 $("<input type=" + type + " />").attr({ name: this.name, value: this.value, checked: this.checked}).insertBefore(this);
//				}).remove();
//
//				$('.classic_answer').css('display', 'block');
//			},
//
//			displayFreeAnswer: function() {
//				$('.free_answer').css('display', 'block');
//			},
//
//			displaySortAnswer: function() {
//				$('.sort_answer').css('display', 'block');
//			},
//
//			displayMatrixSortAnswer: function() {
//				$('.matrix_sort_answer').show();
//			},
//
//			displayClozeAnswer: function() {
//				$('.cloze_answer').show();
//			},
//
//			addAnswer: function(obj) {
//				$(obj).siblings('ul').children().first()
//						.clone().css('display', 'block')
//						.appendTo($(obj).siblings('ul'));
//
//				formListener.setValueClassicAnswer();
//
//				$('.deleteAnswer').click(function(e) {
//					formListener.deleteAnswer(this);
//				});
//			},
//
//			deleteAnswer: function(obj) {
//				$(obj).parent().parent('li').remove();
//
//				formListener.setValueClassicAnswer();
//			},
//
//			setValueClassicAnswer: function() {
//
//				$('.classic_answer ul, .matrix_sort_answer ul, .sort_answer ul').children().each(function() {
//					var index = $(this).index();
//
//					$(this).find( 'input[name="answerJson[classic_answer][correct][]"], '
//								+ 'input[name="answerJson[classic_answer][html][]"], '
//								+ 'input[name="answerJson[answer_matrix_sort][answer_html][]"], '
//								+ 'input[name="answerJson[answer_matrix_sort][sort_string_html][]"], '
//								+ 'input[name="answerJson[answer_sort][html][]"]').val(index);
//				});
//			}
//		};
//
//		var validate = function () {
//
//			var question = '';
//			var type = $('input[name="answerType"]:checked');
//			var $points = $('input[name="points"]');
//
//			if(tinymce.editors.question != undefined && !tinymce.editors.question.isHidden()) {
//				question = tinymce.editors.question.getContent();
//			} else {
//				question = $('textarea[name="question"]').val();
//			}
//
//			if(isNaN($points.val()) || $points.val() < 1) {
//				alert(wpAdvQuizLocalize.no_nummber_points);
//				$points.focus();
//				return false;
//			}
//
//			if(isEmpty(question)) {
//				alert(wpAdvQuizLocalize.no_question_msg);
//				return false;
//			}
//
//
//			if(type.val() == 'single' || type.val() == 'multiple') {
//				var findChecked = true;
//				var findPoints = true;
//				if($('input[name="answerJson[classic_answer][correct][]"]:checked').each(function() {
//					if($.trim($(this).parent().parent().parent().parent().find('textarea').val()) != '') {
//						findChecked &= true;
//					} else {
//						findChecked = false;
//					}
//				})
//				.size() < 1) {
//					alert(wpAdvQuizLocalize.no_correct_msg);
//					return false;
//				}
//
//				if($('input[name="answerPointsActivated"]:checked').length) {
//					$('input[name="answerJson[classic_answer][points][]"]').each(function() {
//						if($.trim($(this).parentsUntil('table').find('textarea').val()) != '') {
//							var points = $.trim($(this).val());
//
//							if(isNaN(points) || points == '' || points < 0) {
//								findPoints = false;
//							} else {
//								findPoints &= true;
//							}
//						}
//					});
//
//					if(!findPoints) {
//						alert(wpAdvQuizLocalize.no_nummber_points_new);
//						return false;
//					}
//				}
//
//				if(!findChecked) {
//					alert(wpAdvQuizLocalize.no_answer_msg);
//					return false;
//				}
//			} else if(type.val() == 'sort_answer') {
//				var findChecked = false;
//				var findPoints = true;
//
//				$('textarea[name="answerJson[answer_sort][answer][]"]').each(function() {
//					if(isEmpty($(this).val())) {
//						findChecked |= false;
//					} else {
//						findChecked = true;
//					}
//				});
//
//				if($('input[name="answerPointsActivated"]:checked').length) {
//					$('input[name="answerJson[answer_sort][points][]"]').each(function() {
//						if($.trim($(this).parentsUntil('table').find('textarea').val()) != '') {
//							var points = $.trim($(this).val());
//
//							if(isNaN(points) || points == '' || points < 0) {
//								findPoints = false;
//							} else {
//								findPoints &= true;
//							}
//						}
//					});
//
//					if(!findPoints) {
//						alert(wpAdvQuizLocalize.no_nummber_points_new);
//						return false;
//					}
//				}
//
//				if(!findChecked) {
//					alert(wpAdvQuizLocalize.no_answer_msg);
//					return false;
//				}
//			} else if(type.val() == 'matrix_sort_answer') {
//				var findChecked = false;
//				var findPoints = true;
//				$('textarea[name="answerJson[answer_matrix_sort][answer][]"]').each(function() {
//					if(isEmpty($(this).val())) {
//						findChecked |= false;
//					} else {
//
//						var $sortString = $(this).parent().parent().find('textarea[name="answerJson[answer_matrix_sort][sort_string][]"]');
//
//						if(isEmpty($sortString.val())) {
//							findChecked |= false;
//						} else {
//							findChecked = true;
//						}
//					}
//				});
//
//				if($('input[name="answerPointsActivated"]:checked').length) {
//					$('input[name="answerJson[answer_matrix_sort][points][]"]').each(function() {
//						if($.trim($(this).parentsUntil('table').find('textarea').val()) != '') {
//							var points = $.trim($(this).val());
//
//							if(isNaN(points) || points == '' || points < 0) {
//								findPoints = false;
//							} else {
//								findPoints &= true;
//							}
//						}
//					});
//
//					if(!findPoints) {
//						alert(wpAdvQuizLocalize.no_nummber_points_new);
//						return false;
//					}
//				}
//
//				if(!findChecked) {
//					alert(wpAdvQuizLocalize.no_answer_msg);
//					return false;
//				}
//			} else if(type.val() == 'cloze_answer') {
//				var clozeText = '';
//
//				if(tinymce.editors.cloze != undefined && !tinymce.editors.cloze.isHidden()) {
//					clozeText = tinymce.editors.cloze.getContent();
//				} else {
//					clozeText = $('textarea[name="answerJson[answer_cloze][text]"]').val();
//				}
//
//				if(isEmpty(clozeText)) {
//					alert(wpAdvQuizLocalize.no_answer_msg);
//					return false;
//				}
//			} else if(type.val() == 'free_answer') {
//				var freeText = $('textarea[name="answerJson[free_answer][correct]"]').val();
//
//				if(isEmpty(freeText)) {
//					alert(wpAdvQuizLocalize.no_answer_msg);
//					return false;
//				}
//			}
//
//			return true;
//		};
//
//		var isEmpty = function(str) {
//			str = $.trim(str);
//			return (!str || 0 === str.length);
//		};
//
//		formListener.setup();
//		setup();
//	};

    //$.fn.wpAdvQuiz_questionOverall = function() {
    //
    //	var methode = {
    //		saveSort: function() {
    //
    //			var data = {
    //				action: 'wp_adv_quiz_update_sort',
    //				sort: methode.parseSortArray()
    //			};
    //
    //			var location = window.location.pathname + window.location.search;
    //			var url = location.replace('admin.php', 'admin-ajax.php') + '&action=save_sort';
    //
    //			$.post(url, data, function(response) {
    //				$('#sortMsg').show(400).delay(1000).hide(400);
    //			});
    //		},
    //
    //		parseSortArray: function() {
    //			var array = new Array();
    //
    //			$('tbody tr').each(function() {
    //				array.push(this.id.replace('wpAdvQuiz_questionId_', ''));
    //			});
    //
    //			return array;
    //		},
    //
    //		sortUpdate: function(e, ui) {
    //			$('.wpAdvQuiz_questionOverall tbody').children().each(function() {
    //				$t = $(this).children().first().text($(this).index() + 1);
    //			});
    //		},
    //
    //		loadQuestionCopy: function() {
    //			var list = $('#questionCopySelect');
    //			var location = window.location.pathname + window.location.search;
    //			var url = location.replace('admin.php', 'admin-ajax.php') + '&action=load_question';
    //			var data = {
    //				action: 'wp_adv_quiz_load_question',
    //				excludeId: 1
    //			};
    //
    //			list.hide();
    //			list.empty();
    //
    //			$('#loadDataImg').show();
    //
    //			$.post(
    //				url,
    //				data,
    //				function(json) {
    //					$.each(json, function(i, v) {
    //
    //						var group = $(document.createElement('optgroup'));
    //
    //						group.attr('label', v.name);
    //
    //						$.each(v.question, function(qi, qv) {
    //							$(document.createElement('option'))
    //								.val(qv.id)
    //								.text(qv.name)
    //								.appendTo(group);
    //
    //
    //						});
    //
    //						list.append(group);
    //
    //					});
    //
    //					$('#loadDataImg').hide();
    //					list.show();
    //				},
    //				'json'
    //			);
    //		}
    //	};
    //
    //	var init = function() {
    //		$('.wp-list-table tbody').sortable({ handle: '.wpAdvQuiz_move', update: methode.sortUpdate });
    //
    //		$('.wpAdvQuiz_delete').click(function(e) {
    //			var b = confirm(wpAdvQuizLocalize.delete_msg);
    //
    //			if(!b) {
    //				e.preventDefault();
    //				return false;
    //			}
    //
    //			return true;
    //		});
    //
    //		$('#wpAdvQuiz_saveSort').click(function(e) {
    //			e.preventDefault();
    //			methode.saveSort();
    //		});
    //
    //		$('#wpAdvQuiz_questionCopy').click(function(e) {
    //			var $this = $('.wpAdvQuiz_questionCopy');
    //
    //			if($this.is(':visible')) {
    //				$this.hide();
    //			} else {
    //				$this.show();
    //				methode.loadQuestionCopy();
    //			}
    //
    //			e.preventDefault();
    //		});
    //	};
    //
    //	init();
    //};

    $.fn.wpAdvQuiz_quizEdit = function () {

        function ajaxPost(func, data, success) {
            var d = {
                action: 'wp_adv_quiz_admin_ajax',
                func: func,
                data: data
            };

            $.post(ajaxurl, d, success, 'json');
        };

        var methode = {
            addCategory: function () {
                var name = $.trim($('input[name="categoryAdd"]').val());

                if (isEmpty(name)) {
                    return;
                }

                var data = {
                    categoryName: name,
                    type: 'quiz'
                };

                ajaxPost('categoryAdd', data, function (json) {
                    if (json.err) {
                        $('#categoryMsgBox').text(json.err).show('fast').delay(2000).hide('fast');
                        return;
                    }

                    var $option = $(document.createElement('option'))
                        .val(json.categoryId)
                        .text(json.categoryName)
                        .attr('selected', 'selected');

                    $('select[name="category"]').append($option).change();

                });
            },

            addResult: function () {
                $('#resultList').children().each(function () {
                    if ($(this).css('display') == 'none') {
                        //TODO rework
                        var $this = $(this);
                        var $text = $this.find('textarea[name="resultTextGrade[text][]"]');
                        var id = $text.attr('id');
                        var hidden = true;

                        $this.find('input[name="resultTextGrade[prozent][]"]').val('0');
                        $this.find('input[name="resultTextGrade[activ][]"]').val('1').keyup();

                        if (tinymce.editors[id] != undefined && !tinymce.editors[id].isHidden()) {
                            hidden = false;
                        }

                        if (switchEditors != undefined && !hidden) {
                            switchEditors.go(id, 'toggle');
                            switchEditors.go(id, 'toggle');
                        }

                        if (tinymce.editors[id] != undefined) {
                            tinymce.editors[id].setContent('');
                        } else {
                            $text.val('');
                        }

                        if (tinymce.editors[id] != undefined && !hidden) {
                            tinyMCE.execCommand('mceRemoveControl', false, id);
                        }

                        $this.parent().children(':visible').last().after($this);

                        if (tinymce.editors[id] != undefined && !hidden) {
                            tinyMCE.execCommand('mceAddControl', false, id);
                        }

                        $(this).show();

                        if (switchEditors != undefined && !hidden) {
                            switchEditors.go(id, 'toggle');
                        }

                        return false;
                    }
                });
            },

            deleteResult: function (e) {
                $(e).parent().parent().hide();
                $(e).siblings('input[name="resultTextGrade[activ][]"]').val('0');
            },

            changeResult: function (e) {
                var $this = $(e);

                if (methode.validResultInput($this.val())) {
                    $this.siblings('.resultAdvzent').text($this.val());
                    $this.removeAttr('style');
                    return true;
                }

                $this.css('background-color', '#FF9696');

                return false;
            },

            validResultInput: function (input) {

                if (isEmpty(input))
                    return false;

                input = input.replace(/\,/, '.');

                if (!isNaN(input) && Number(input) <= 100 && Number(input) >= 0) {
                    if (input.match(/\./) != null)
                        return input.split('.')[1].length < 3;

                    return true;
                }

                return false;
            },

            validInput: function () {
                if (isEmpty($('#wpAdvQuiz_title').val())) {
                    alert(wpAdvQuizLocalize.no_title_msg);
                    return false;
                }

                var text = '';

                if (tinymce.editors.text != undefined && !tinymce.editors.text.isHidden()) {
                    text = tinymce.editors.text.getContent();
                } else {
                    text = $('textarea[name="text"]').val();
                }

                if (isEmpty(text)) {
                    alert(wpAdvQuizLocalize.no_quiz_start_msg);
                    return false;
                }

                if ($('#wpAdvQuiz_resultGradeEnabled:checked').length) {
                    var rCheck = true;

                    $('#resultList').children().each(function () {
                        if ($(this).is(':visible')) {
                            if (!methode.validResultInput($(this).find('input[name="resultTextGrade[prozent][]"]').val())) {
                                rCheck = false;
                                return false;
                            }
                        }
                    });

                    if (!rCheck) {
                        alert(wpAdvQuizLocalize.fail_grade_result);
                        return false;
                    }
                }

                return true;
            },

            resetLock: function () {
                //var location = window.location.pathname + window.location.search;
                //var url = location.replace('admin.php', 'admin-ajax.php');
                //url = url.replace('action=edit', 'action=reset_lock');
                //
                //$.post(url, {
                //    action: 'wp_adv_quiz_reset_lock'
                //}, function (data) {
                //    $('#resetLockMsg').show('fast').delay(2000).hide('fast');
                //});

                ajaxPost('resetLock', {
                    quizId: $('input[name="ajax_quiz_id"]').val()
                }, function () {
                    $('#resetLockMsg').show('fast').delay(2000).hide('fast');
                });
            },

            generateFormIds: function () {
                var index = 0;

                $('#form_table tbody > tr').each(function () {
                    $(this).find('[name^="form[]"]').each(function () {
                        var newname = $(this).attr('name').substr(6);
                        $(this).attr('name', 'form[' + index + ']' + newname);
                    });

                    ++index;
                });
            },

            updateFormIds: function () {
                var index = -1;
                var selected = $('.emailFormVariables option:selected').val();
                var $formVariables = $('.formVariables').empty();
                var $emailFormVariables = $('.emailFormVariables').empty().append('<option value="-1"></option>');

                if ($('.emailFormVariables').data('default') > -1) {
                    selected = $('.emailFormVariables').data('default');
                    $('.emailFormVariables').data('default', -1);
                }

                $('#form_table tbody > tr').each(function () {
                    $(this).children().first().text(index);
                    var fieldName = $(this).find('.formFieldName').val();
                    var type = $(this).find('[name="form[][type]"] option:selected');
                    var name = $(this).find('[name="form[][fieldname]"]').val();

                    //is deleted?
                    if ($(this).find('input[name="form[][form_delete]"]').val() == 1)
                        return;

                    if (index >= 0 && !isEmpty(fieldName))
                        $formVariables.append($('<li><span>$form{' + index + '}</span> - ' + fieldName + '</li>'));

                    if (type.val() == 4)
                        $emailFormVariables.append($('<option value="' + index + '">' + name + '</option>'))

                    index++;
                });

                $('.emailFormVariables option[value="' + selected + '"]').prop('selected', true);
            }

        };

        var isEmpty = function (str) {
            str = $.trim(str);
            return (!str || 0 === str.length);
        };

        var init = function () {
            $('#statistics_on').change(function () {
                if (this.checked) {
                    $('#statistics_ip_lock_tr').show();
                } else {
                    $('#statistics_ip_lock_tr').hide();
                }
            });

            $('.addResult').click(function () {
                methode.addResult();
            });

            $('.deleteResult').click(function (e) {
                methode.deleteResult(this);
            });

            $('input[name="resultTextGrade[prozent][]"]').keyup(function (event) {
                methode.changeResult(this);
            }).keydown(function (event) {
                if (event.which == 13) {
                    event.preventDefault();
                }
            });

            $('#wpAdvQuiz_resultGradeEnabled').change(function () {
                if (this.checked) {
                    $('#resultGrade').show();
                    $('#resultNormal').hide();
                } else {
                    $('#resultGrade').hide();
                    $('#resultNormal').show();
                }
            });

            $('#wpAdvQuiz_save').click(function (e) {
                if (!methode.validInput())
                    e.preventDefault();
                else
                    methode.generateFormIds();

                $('select[name="prerequisiteList[]"] option').attr('selected', 'selected');
            });

            $('input[name="template"]').click(function (e) {
                if ($('select[name="templateSaveList"]').val() == '0') {
                    if (isEmpty($('input[name="templateName"]').val())) {
                        alert(wpAdvQuizLocalize.temploate_no_name);

                        e.preventDefault();
                        return false;
                    }
                }

                methode.generateFormIds();
                $('select[name="prerequisiteList[]"] option').attr('selected', 'selected');
            });

            $('select[name="templateSaveList"]').change(function () {
                var $templateName = $('input[name="templateName"]');

                if ($(this).val() == '0') {
                    $templateName.show();
                } else {
                    $templateName.hide();
                }
            }).change();

            $('input[name="quizRunOnce"]').change(function (e) {
                if (this.checked) {
                    $('#wpAdvQuiz_quiz_run_once_type').show();
                    $('input[name="quizRunOnceType"]:checked').change();
                } else {
                    $('#wpAdvQuiz_quiz_run_once_type').hide();
                }
            });

            $('input[name="quizRunOnceType"]').change(function (e) {
                if (this.checked && (this.value == "1" || this.value == "3")) {
                    $('#wpAdvQuiz_quiz_run_once_cookie').show();
                } else {
                    $('#wpAdvQuiz_quiz_run_once_cookie').hide();
                }
            });

            $('input[name="resetQuizLock"]').click(function (e) {
                methode.resetLock();

                return false;
            });

            $('.wpAdvQuiz_demoBox a').mouseover(function (e) {
                var $this = $(this);
                var d = $('#poststuff').width();
                var img = $this.siblings().outerWidth(true);

                if (e.pageX + img > d) {
                    //var v = d + (e.pageX - (e.pageX + img + 30));
                    var v = jQuery(document).width() - $this.parent().offset().left - img - 30;
                    $(this).next().css('left', v + "px");
                }

                $(this).next().show();

            }).mouseout(function () {
                $(this).next().hide();
            }).click(function () {
                return false;
            });

            $('input[name="showMaxQuestion"]').change(function () {
                if (this.checked) {
//					$('input[name="statisticsOn"]').removeAttr('checked').attr('disabled', 'disabled').change();
                    $('#wpAdvQuiz_showMaxBox').show();
                } else {
//					$('input[name="statisticsOn"]').removeAttr('disabled');
                    $('#wpAdvQuiz_showMaxBox').hide();
                }
            });

            $('#btnPrerequisiteAdd').click(function () {
                $('select[name="quizList"] option:selected').removeAttr('selected').appendTo('select[name="prerequisiteList[]"]');
            });

            $('#btnPrerequisiteDelete').click(function () {
                $('select[name="prerequisiteList[]"] option:selected').removeAttr('selected').appendTo('select[name="quizList"]');
            });

            $('input[name="prerequisite"]').change(function () {
                if (this.checked)
                    $('#prerequisiteBox').show();
                else
                    $('#prerequisiteBox').hide();

            }).change();

            $('input[name="toplistDataAddMultiple"]').change(function () {
                if (this.checked)
                    $('#toplistDataAddBlockBox').show();
                else
                    $('#toplistDataAddBlockBox').hide();

            }).change();

            $('input[name="toplistActivated"]').change(function () {
                if (this.checked)
                    $('#toplistBox > tr:gt(0)').show();
                else
                    $('#toplistBox > tr:gt(0)').hide();

            }).change();

            $('input[name="showReviewQuestion"]').change(function () {
                if (this.checked) {
                    $('.wpAdvQuiz_reviewQuestionOptions').show();
                } else {
                    $('.wpAdvQuiz_reviewQuestionOptions').hide();
                }
            }).change();

            $('#statistics_on').change();
            $('#wpAdvQuiz_resultGradeEnabled').change();
            $('input[name="quizRunOnce"]').change();
            $('input[name="quizRunOnceType"]:checked').change();
            $('input[name="showMaxQuestion"]').change();

            $('#form_add').click(function () {
                $('#form_table tbody > tr:eq(0)').clone(true).appendTo('#form_table tbody').show();
                methode.updateFormIds();
            });

            $('input[name="form_delete"]').click(function () {
                var con = $(this).parents('tr');

                if (con.find('input[name="form[][form_id]"]').val() != "0") {
                    con.find('input[name="form[][form_delete]"]').val(1);
                    con.hide();
                } else {
                    con.remove();
                }

                methode.updateFormIds();
            });

            $('#form_table tbody').sortable({
                handle: '.form_move',
                update: methode.updateFormIds
            });
            $('.form_move').click(function () {
                return false;
            });

            $('select[name="form[][type]"]').change(function () {
                switch (Number($(this).val())) {
                    case 7:
                    case 8:
                        $(this).siblings('.editDropDown').show();
                        break;
                    default:
                        $(this).siblings('.editDropDown, .dropDownEditBox').hide();
                        break;
                }

            }).change();

            $('.editDropDown').click(function () {
                $('.dropDownEditBox').not(
                    $(this).siblings('.dropDownEditBox').toggle())
                    .hide();

                return false;
            });

            $('.dropDownEditBox input').click(function () {
                $(this).parent().hide();
            });

            $('.formFieldName, select[name="form[][type]"]').change(function () {
                methode.updateFormIds();
            });

            $('select[name="category"]').change(function () {
                var $this = $(this);
                var box = $('#categoryAddBox').hide();

                if ($this.val() == "-1") {
                    box.show();
                }

            }).change();

            $('#categoryAddBtn').click(function () {
                methode.addCategory();
            });

            $('input[name="emailNotification"]').change(function () {
                var $tr = $('#adminEmailSettings tr:gt(0)');

                if ($('input[name="emailNotification"]:checked').val() > 0) {
                    $tr.show();
                } else {
                    $tr.hide();
                }
            }).change();

            $('input[name="userEmailNotification"]').change(function () {
                var $tr = $('#userEmailSettings tr:gt(0)');

                if ($('input[name="userEmailNotification"]:checked').val() > 0) {
                    $tr.show();
                } else {
                    $tr.hide();
                }
            }).change();

            methode.updateFormIds();

            $('input[name="email[html]"]').change(function () {
                if (switchEditors == undefined)
                    return false;

                if (this.checked) {
                    switchEditors.go('adminEmailEditor', 'tmce');
                } else {
                    switchEditors.go('adminEmailEditor', 'html');
                }

            });

            $('input[name="adminEmail[html]"]').change(function () {
                if (switchEditors == undefined)
                    return false;

                if (this.checked) {
                    switchEditors.go('adminEmailEditor', 'tmce');
                } else {
                    switchEditors.go('adminEmailEditor', 'html');
                }

            });

            $('input[name="userEmail[html]"]').change(function () {
                if (switchEditors == undefined)
                    return false;

                if (this.checked) {
                    switchEditors.go('userEmailEditor', 'tmce');
                } else {
                    switchEditors.go('userEmailEditor', 'html');
                }

            });

            setTimeout(function () {
                $('input[name="userEmail[html]"]').change();
                $('input[name="email[html]"]').change();
            }, 1000);
        };

        init();
    };

    $.fn.wpAdvQuiz_statistics = function () {
        var currectTab = 'wpAdvQuiz_typeAnonymeUser';
        var changePageNav = true;

        var methode = {
            loadStatistics: function (userId) {
                var location = window.location.pathname + window.location.search;
                var url = location.replace('admin.php', 'admin-ajax.php') + '&action=load_statistics';
                var data = {
                    action: 'wp_adv_quiz_load_statistics',
                    userId: userId
                };

                $('#wpAdvQuiz_loadData').show();
                $('#wpAdvQuiz_statistics_content, #wpAdvQuiz_statistics_overview').hide();

                $.post(
                    url,
                    data,
                    methode.setStatistics,
                    'json'
                );
            },

            setStatistics: function (json) {
                var $table = $('.wpAdvQuiz_statistics_table');
                var $tbody = $table.find('tbody');

                if (currectTab == 'wpAdvQuiz_typeOverview') {
                    return;
                }

                var setItem = function (i, j, r) {
                    i.find('.wpAdvQuiz_cCorrect').text(j.cCorrect + ' (' + j.pCorrect + '%)');
                    i.find('.wpAdvQuiz_cIncorrect').text(j.cIncorrect + ' (' + j.pIncorrect + '%)');
                    i.find('.wpAdvQuiz_cTip').text(j.cTip);
                    i.find('.wpAdvQuiz_cPoints').text(j.cPoints);

                    if (r == true) {
                        $table.find('.wpAdvQuiz_cResult').text(j.result + '%');
                    }
                };

                setItem($table, json.clear, false);

                $.each(json.items, function (i, v) {
                    setItem($tbody.find('#wpAdvQuiz_tr_' + v.id), v, false);
                });

                setItem($table.find('tfoot'), json.global, true);

                $('#wpAdvQuiz_loadData').hide();
                $('#wpAdvQuiz_statistics_content, .wpAdvQuiz_statistics_table').show();
            },

            loadOverview: function () {
                $('.wpAdvQuiz_statistics_table, #wpAdvQuiz_statistics_content, #wpAdvQuiz_statistics_overview').hide();
                $('#wpAdvQuiz_loadData').show();

                var location = window.location.pathname + window.location.search;
                var url = location.replace('admin.php', 'admin-ajax.php') + '&action=load_statistics';
                var data = {
                    action: 'wp_adv_quiz_load_statistics',
                    overview: true,
                    pageLimit: $('#wpAdvQuiz_pageLimit').val(),
                    onlyCompleted: Number($('#wpAdvQuiz_onlyCompleted').is(':checked')),
                    page: $('#wpAdvQuiz_currentPage').val(),
                    generatePageNav: Number(changePageNav)
                };

                $.post(
                    url,
                    data,
                    function (json) {
                        $('#wpAdvQuiz_statistics_overview_data').empty();

                        if (currectTab != 'wpAdvQuiz_typeOverview') {
                            return;
                        }

                        var item = $('<tr>'
                            + '<th><a href="#">---</a></th>'
                            + '<th class="wpAdvQuiz_points">---</th>'
                            + '<th class="wpAdvQuiz_cCorrect" style="color: green;">---</th>'
                            + '<th class="wpAdvQuiz_cIncorrect" style="color: red;">---</th>'
                            + '<th class="wpAdvQuiz_cTip">---</th>'
                            + '<th class="wpAdvQuiz_cResult" style="font-weight: bold;">---</th>'
                            + '</tr>'
                        );

                        $.each(json.items, function (i, v) {
                            var d = item.clone();

                            d.find('a').text(v.userName).data('userId', v.userId).click(function () {
                                $('#userSelect').val($(this).data('userId'));

                                $('#wpAdvQuiz_typeRegisteredUser').click();

                                return false;
                            });

                            if (v.completed) {
                                d.find('.wpAdvQuiz_points').text(v.cPoints);
                                d.find('.wpAdvQuiz_cCorrect').text(v.cCorrect + ' (' + v.pCorrect + '%)');
                                d.find('.wpAdvQuiz_cIncorrect').text(v.cIncorrect + ' (' + v.pIncorrect + '%)');
                                d.find('.wpAdvQuiz_cTip').text(v.cTip);
                                d.find('.wpAdvQuiz_cResult').text(v.result + '%');
                            } else {
                                d.find('th').removeAttr('style');
                            }

                            $('#wpAdvQuiz_statistics_overview_data').append(d);
                        });

                        if (json.page != undefined) {
                            methode.setPageNav(json.page);
                            changePageNav = false;
                        }

                        $('#wpAdvQuiz_loadData').hide();
                        $('#wpAdvQuiz_statistics_overview').show();
                    },
                    'json'
                );
            },

            loadFormOverview: function () {
                $('#wpAdvQuiz_tabFormOverview').show();
            },

            changeTab: function (id) {
                currectTab = id;

                if (id == 'wpAdvQuiz_typeRegisteredUser') {
                    methode.loadStatistics($('#userSelect').val());
                } else if (id == 'wpAdvQuiz_typeAnonymeUser') {
                    methode.loadStatistics(0);
                } else if (id == 'wpAdvQuiz_typeForm') {
                    methode.loadFormOverview();
                } else {
                    methode.loadOverview();
                }
            },

            resetStatistic: function (complete) {
                var userId = (currectTab == 'wpAdvQuiz_typeRegisteredUser') ? $('#userSelect').val() : 0;
                var location = window.location.pathname + window.location.search;
                var url = location.replace('admin.php', 'admin-ajax.php') + '&action=reset';
                var data = {
                    action: 'wp_adv_quiz_statistics',
                    userId: userId,
                    'complete': complete
                };

                $.post(url, data, function (e) {
                    methode.changeTab(currectTab);
                });
            },

            setPageNav: function (page) {
                page = Math.ceil(page / $('#wpAdvQuiz_pageLimit').val());
                $('#wpAdvQuiz_currentPage').empty();

                for (var i = 1; i <= page; i++) {
                    $(document.createElement('option'))
                        .val(i)
                        .text(i)
                        .appendTo($('#wpAdvQuiz_currentPage'));
                }

                $('#wpAdvQuiz_pageLeft, #wpAdvQuiz_pageRight').hide();

                if ($('#wpAdvQuiz_currentPage option').length > 1) {
                    $('#wpAdvQuiz_pageRight').show();

                }
            }
        };

        var init = function () {
            $('.wpAdvQuiz_tab').click(function (e) {
                var $this = $(this);

                if ($this.hasClass('button-primary')) {
                    return false;
                }

                if ($this.attr('id') == 'wpAdvQuiz_typeRegisteredUser') {
                    $('#wpAdvQuiz_userBox').show();
                } else {
                    $('#wpAdvQuiz_userBox').hide();
                }

                $('.wpAdvQuiz_tab').removeClass('button-primary').addClass('button-secondary');
                $this.removeClass('button-secondary').addClass('button-primary');

                methode.changeTab($this.attr('id'));

                return false;
            });

            $('#userSelect').change(function () {
                methode.changeTab('wpAdvQuiz_typeRegisteredUser');
            });

            $('.wpAdvQuiz_update').click(function () {
                methode.changeTab(currectTab);

                return false;
            });

            $('#wpAdvQuiz_reset').click(function () {

                var c = confirm(wpAdvQuizLocalize.reset_statistics_msg);

                if (c) {
                    methode.resetStatistic(false);
                }

                return false;
            });

            $('.wpAdvQuiz_resetComplete').click(function () {

                var c = confirm(wpAdvQuizLocalize.reset_statistics_msg);

                if (c) {
                    methode.resetStatistic(true);
                }

                return false;
            });

            $('#wpAdvQuiz_pageLimit, #wpAdvQuiz_onlyCompleted').change(function () {
                $('#wpAdvQuiz_currentPage').val(0);
                changePageNav = true;
                methode.changeTab(currectTab);

                return false;
            });

            $('#wpAdvQuiz_currentPage').change(function () {
                $('#wpAdvQuiz_pageLeft, #wpAdvQuiz_pageRight').hide();

                if ($('#wpAdvQuiz_currentPage option').length == 1) {

                } else if ($('#wpAdvQuiz_currentPage option:first-child:selected').length) {
                    $('#wpAdvQuiz_pageRight').show();
                } else if ($('#wpAdvQuiz_currentPage option:last-child:selected').length) {
                    $('#wpAdvQuiz_pageLeft').show();
                } else {
                    $('#wpAdvQuiz_pageLeft, #wpAdvQuiz_pageRight').show();
                }

                methode.changeTab(currectTab);
            });

            $('#wpAdvQuiz_pageRight').click(function () {
                $('#wpAdvQuiz_currentPage option:selected').next().attr('selected', 'selected');
                $('#wpAdvQuiz_currentPage').change();

                return false;
            });

            $('#wpAdvQuiz_pageLeft').click(function () {
                $('#wpAdvQuiz_currentPage option:selected').prev().attr('selected', 'selected');
                $('#wpAdvQuiz_currentPage').change();

                return false;
            });

            methode.changeTab('wpAdvQuiz_typeAnonymeUser');
        };

        init();
    };

    $.fn.wpAdvQuiz_toplist = function () {
        function ajaxPost(func, data, success) {
            var d = {
                action: 'wp_adv_quiz_admin_ajax',
                func: func,
                data: data
            };

            $.post(ajaxurl, d, success, 'json');
        }

        var elements = {
            sort: $('#wpAdvQuiz_sorting'),
            pageLimit: $('#wpAdvQuiz_pageLimit'),
            currentPage: $('#wpAdvQuiz_currentPage'),
            loadDataBox: $('#wpAdvQuiz_loadData'),
            pageLeft: $('#wpAdvQuiz_pageLeft'),
            pageRight: $('#wpAdvQuiz_pageRight'),
            dataBody: $('#wpAdvQuiz_toplistTable tbody'),
            rowClone: $('#wpAdvQuiz_toplistTable tbody tr:eq(0)').clone(),
            content: $('#wpAdvQuiz_content')
        };

        var methods = {
            loadData: function (action) {
                //var location = window.location.pathname + window.location.search;
                //var url = location.replace('admin.php', 'admin-ajax.php') + '&action=load_toplist';
                var th = this;
                var data = {
                    //action: 'wp_adv_quiz_load_toplist',
                    sort: elements.sort.val(),
                    limit: elements.pageLimit.val(),
                    page: elements.currentPage.val(),
                    quizId: $('input[name="ajax_quiz_id"]').val()
                };

                if (action != undefined) {
                    $.extend(data, action);
                }

                elements.loadDataBox.show();
                elements.content.hide();

                //$.post(url, data, function (json) {
                //    //methods.handleDataRequest(json.data);
                //    th.handleDataRequest(json.data);
                //
                //    if (json.nav != undefined) {
                //        //methods.handleNav(json.nav);
                //        th.handleNav(json.nav);
                //    }
                //
                //    elements.loadDataBox.hide();
                //    elements.content.show();
                //}, 'json');

                ajaxPost('adminToplist', data, function (json) {
                    th.handleDataRequest(json.data);

                    if (json.nav != undefined) {
                        th.handleNav(json.nav);
                    }

                    elements.loadDataBox.hide();
                    elements.content.show();
                });
            },

            handleNav: function (nav) {
                elements.currentPage.empty();

                for (var i = 1; i <= nav.pages; i++) {
                    $(document.createElement('option'))
                        .val(i).text(i)
                        .appendTo(elements.currentPage);
                }

                this.checkNav();
            },

            handleDataRequest: function (json) {
                var methods = this;

                elements.dataBody.empty();

                $.each(json, function (i, v) {
                    var data = elements.rowClone.clone().children();

                    data.eq(0).children().val(v.id);
                    data.eq(1).find('strong').text(v.name);
                    data.eq(1).find('.inline_editUsername').val(v.name);
                    data.eq(2).find('.wpAdvQuiz_email').text(v.email);
                    data.eq(2).find('input').val(v.email);
                    data.eq(3).text(v.type);
                    data.eq(4).text(v.date);
                    data.eq(5).text(v.points);
                    data.eq(6).text(v.result);

                    data.parent().show().appendTo(elements.dataBody);
                });

                if (!json.length) {
                    $(document.createElement('td'))
                        .attr('colspan', '7')
                        .text(wpAdvQuizLocalize.no_data_available)
                        .css({
                            'font-weight': 'bold',
                            'text-align': 'center',
                            'padding': '5px'
                        })
                        .appendTo(document.createElement('tr'))
                        .appendTo(elements.dataBody);
                }

                $('.wpAdvQuiz_delete').click(function () {
                    if (confirm(wpAdvQuizLocalize.confirm_delete_entry)) {
                        var id = new Array($(this).closest('tr').find('input[name="checkedData[]"]').val());

                        methods.loadData({
                            a: 'delete',
                            toplistIds: id
                        });
                    }

                    return false;
                });

                $('.wpAdvQuiz_edit').click(function () {
                    var $contain = $(this).closest('tr');

                    $contain.find('.row-actions').hide();
                    $contain.find('.inline-edit').show();

                    $contain.find('.wpAdvQuiz_username, .wpAdvQuiz_email').hide();
                    $contain.find('.inline_editUsername, .inline_editEmail').show();

                    return false;
                });

                $('.inline_editSave').click(function () {
                    var $contain = $(this).closest('tr');
                    var username = $contain.find('.inline_editUsername').val();
                    var email = $contain.find('.inline_editEmail').val();

                    if (methods.isEmpty(username) || methods.isEmpty(email)) {
                        alert(wpAdvQuizLocalize.not_all_fields_completed);

                        return false;
                    }

                    methods.loadData({
                        a: 'edit',
                        toplistId: $contain.find('input[name="checkedData[]"]').val(),
                        name: username,
                        email: email
                    });

                    return false;
                });

                $('.inline_editCancel').click(function () {
                    var $contain = $(this).closest('tr');

                    $contain.find('.row-actions').show();
                    $contain.find('.inline-edit').hide();

                    $contain.find('.wpAdvQuiz_username, .wpAdvQuiz_email').show();
                    $contain.find('.inline_editUsername, .inline_editEmail').hide();

                    $contain.find('.inline_editUsername').val($contain.find('.wpAdvQuiz_username').text());
                    $contain.find('.inline_editEmail').val($contain.find('.wpAdvQuiz_email').text());

                    return false;
                });
            },

            checkNav: function () {
                var n = elements.currentPage.val();

                if (n == 1) {
                    elements.pageLeft.hide();
                } else {
                    elements.pageLeft.show();
                }

                if (n == elements.currentPage.children().length) {
                    elements.pageRight.hide();
                } else {
                    elements.pageRight.show();
                }
            },

            isEmpty: function (text) {
                text = $.trim(text);

                return (!text || 0 === text.length);
            }
        };

        var init = function () {
            elements.sort.change(function () {
                methods.loadData();
            });

            elements.pageLimit.change(function () {
                methods.loadData({nav: 1});
            });

            elements.currentPage.change(function () {
                methods.checkNav();
                methods.loadData();
            });

            elements.pageLeft.click(function () {
                elements.currentPage.val(Number(elements.currentPage.val()) - 1);
                methods.checkNav();
                methods.loadData();
            });

            elements.pageRight.click(function () {
                elements.currentPage.val(Number(elements.currentPage.val()) + 1);
                methods.checkNav();
                methods.loadData();
            });

            $('#wpAdvQuiz_deleteAll').click(function () {
                methods.loadData({a: 'deleteAll'});
            });

            $('#wpAdvQuiz_action').click(function () {
                var name = $('#wpAdvQuiz_actionName').val();

                if (name != '0') {

                    var ids = $('input[name="checkedData[]"]:checked').map(function () {
                        return $(this).val();
                    }).get();

                    methods.loadData({
                        a: name,
                        toplistIds: ids
                    });
                }
            });

            $('#wpAdvQuiz_checkedAll').change(function () {
                if (this.checked)
                    $('input[name="checkedData[]"]').attr('checked', 'checked');
                else
                    $('input[name="checkedData[]"]').removeAttr('checked', 'checked');
            });

            methods.loadData({nav: 1});
        };

        init();
    };

    if ($('.wpAdvQuiz_quizOverall').length)
        $('.wpAdvQuiz_quizOverall').wpAdvQuiz_preview();

    if ($('.wpAdvQuiz_quizOverall').length) {
        $('.wpAdvQuiz_quizOverall').wpAdvQuiz_quizOverall();
    }

    if ($('.wpAdvQuiz_quizEdit').length)
        $('.wpAdvQuiz_quizEdit').wpAdvQuiz_quizEdit();

//	if($('.wpAdvQuiz_questionEdit').length)
//		$('.wpAdvQuiz_questionEdit').wpAdvQuiz_questionEdit();

    //if($('.wpAdvQuiz_questionOverall').length)
    //	$('.wpAdvQuiz_questionOverall').wpAdvQuiz_questionOverall();

//	if($('.wpAdvQuiz_statistics').length)
//		$('.wpAdvQuiz_statistics').wpAdvQuiz_statistics();

    if ($('.wpAdvQuiz_toplist').length)
        $('.wpAdvQuiz_toplist').wpAdvQuiz_toplist();

    /**
     * NEW
     */
    /**
     * @memberOf WpAdvQuiz_Admin
     */
    function WpAdvQuiz_Admin() {
        var global = this;

        global = {
            displayChecked: function (t, box, neg, disabled) {
                var c = neg ? !t.checked : t.checked;

                if (disabled)
                    c ? box.attr('disabled', 'disabled') : box.removeAttr('disabled');
                else
                    c ? box.show() : box.hide();
            },

            isEmpty: function (text) {
                text = $.trim(text);

                return (!text || 0 === text.length);
            },

            isNumber: function (number) {
                number = $.trim(number);
                return !global.isEmpty(number) && !isNaN(number);
            },

            getMceContent: function (id) {
                var editor = tinymce.editors[id];

                if (editor != undefined && !editor.isHidden()) {
                    return editor.getContent();
                }

                return $('#' + id).val();
            },

            ajaxPost: function (func, data, success) {
                var d = {
                    action: 'wp_adv_quiz_admin_ajax',
                    func: func,
                    data: data
                };

                $.post(ajaxurl, d, success, 'json');
            }
        };

        var tabWrapper = function () {
            $('.wpAdvQuiz_tab_wrapper a').click(function () {
                var $this = $(this);
                var tabId = $this.data('tab');
                var currentTab = $this.siblings('.button-primary').removeClass('button-primary').addClass('button-secondary');

                $this.removeClass('button-secondary').addClass('button-primary');

                $(currentTab.data('tab')).hide('fast');
                $(tabId).show('fast');

                $(document).trigger({
                    type: 'changeTab',
                    tabId: tabId
                });

                return false;
            });
        };

        var module = {
            /**
             * @memberOf WpAdvQuiz_admin.module
             */

            gobalSettings: function () {
                var methode = {
                    categoryDelete: function (id, type) {
                        var data = {
                            categoryId: id
                        };

                        global.ajaxPost('categoryDelete', data, function (json) {
                            if (json.err) {

                                return;
                            }

                            $('select[name="category' + type + '"] option[value="' + id + '"]').remove();
                            $('select[name="category' + type + '"]').change();
                        });
                    },

                    categoryEdit: function (id, name, type) {
                        var data = {
                            categoryId: id,
                            categoryName: $.trim(name)
                        };

                        if (global.isEmpty(name)) {
                            alert(wpAdvQuizLocalize.category_no_name);
                            return;
                        }

                        global.ajaxPost('categoryEdit', data, function (json) {
                            if (json.err) {

                                return;
                            }

                            $('select[name="category' + type + '"] option[value="' + id + '"]').text(data.categoryName);
                            $('select[name="category' + type + '"]').change();
                        });
                    },

                    changeTimeFormat: function (inputName, $select) {
                        if ($select.val() != "0")
                            $('input[name="' + inputName + '"]').val($select.val());
                    },

                    templateDelete: function (id, type) {
                        var data = {
                            templateId: id,
                            type: type
                        };

                        global.ajaxPost('templateDelete', data, function (json) {
                            if (json.err) {

                                return;
                            }

                            if (!type) {
                                $('select[name="templateQuiz"] option[value="' + id + '"]').remove();
                                $('select[name="templateQuiz"]').change();
                            } else {
                                $('select[name="templateQuestion"] option[value="' + id + '"]').remove();
                                $('select[name="templateQuestion"]').change();
                            }
                        });
                    },

                    templateEdit: function (id, name, type) {

                        if (global.isEmpty(name)) {
                            alert(wpAdvQuizLocalize.category_no_name);
                            return;
                        }

                        var data = {
                            templateId: id,
                            name: $.trim(name),
                            type: type
                        };

                        global.ajaxPost('templateEdit', data, function (json) {
                            if (json.err) {

                                return;
                            }

                            if (!type) {
                                $('select[name="templateQuiz"] option[value="' + id + '"]').text(data.name);
                                $('select[name="templateQuiz"]').change();
                            } else {
                                $('select[name="templateQuestion"] option[value="' + id + '"]').text(data.name);
                                $('select[name="templateQuestion"]').change();
                            }
                        });
                    }
                };

                var init = function () {
//					$('.wpAdvQuiz_tab').click(function() {
//						var $this = $(this);
//
//						$('.wpAdvQuiz_tab').removeClass('button-primary').addClass('button-secondary');
//						$this.removeClass('button-secondary').addClass('button-primary');
//
//						$('#problemInfo, #problemContent, #globalContent').hide('fast');
//
//						if($this.attr('id') == 'globalTab') {
//							$('#globalContent').show('fast');
//						} else {
//							$('#problemInfo, #problemContent').show('fast');
//						}
//					});

                    $('select[name="category"]').change(function () {
                        $('input[name="categoryEditText"]').val($(this).find(':selected').text());
                    }).change();

                    $('input[name="categoryDelete"]').click(function () {
                        var id = $('select[name="category"] option:selected').val();

                        methode.categoryDelete(id, '');
                    });

                    $('input[name="categoryEdit"]').click(function () {
                        var id = $('select[name="category"] option:selected').val();
                        var text = $('input[name="categoryEditText"]').val();

                        methode.categoryEdit(id, text, '');
                    });

                    $('select[name="categoryQuiz"]').change(function () {
                        $('input[name="categoryQuizEditText"]').val($(this).find(':selected').text());
                    }).change();

                    $('input[name="categoryQuizDelete"]').click(function () {
                        var id = $('select[name="categoryQuiz"] option:selected').val();

                        methode.categoryDelete(id, 'Quiz');
                    });

                    $('input[name="categoryQuizEdit"]').click(function () {
                        var id = $('select[name="categoryQuiz"] option:selected').val();
                        var text = $('input[name="categoryQuizEditText"]').val();

                        methode.categoryEdit(id, text, 'Quiz');
                    });

                    $('#statistic_time_format_select').change(function () {
                        methode.changeTimeFormat('statisticTimeFormat', $(this));
                    });

                    $(document).bind('changeTab', function (data) {
                        $('#problemInfo').hide('fast');

                        switch (data.tabId) {
                            case '#problemContent':
                                $('#problemInfo').show('fast');
                                break;
                            case '#emailSettingsTab':
                                break;
                        }
                    });

                    $('input[name="email[html]"]').change(function () {
                        if (switchEditors == undefined)
                            return false;

                        if (this.checked) {
                            switchEditors.go('adminEmailEditor', 'tmce');
                        } else {
                            switchEditors.go('adminEmailEditor', 'html');
                        }

                    }).change();

                    $('input[name="userEmail[html]"]').change(function () {
                        if (switchEditors == undefined)
                            return false;

                        if (this.checked) {
                            switchEditors.go('userEmailEditor', 'tmce');
                        } else {
                            switchEditors.go('userEmailEditor', 'html');
                        }

                    }).change();

                    $('select[name="templateQuiz"]').change(function () {
                        $('input[name="templateQuizEditText"]').val($(this).find(':selected').text());
                    }).change();

                    $('select[name="templateQuestion"]').change(function () {
                        $('input[name="templateQuestionEditText"]').val($(this).find(':selected').text());
                    }).change();

                    $('input[name="templateQuizDelete"]').click(function () {
                        var id = $('select[name="templateQuiz"] option:selected').val();

                        methode.templateDelete(id, 0);
                    });

                    $('input[name="templateQuestionDelete"]').click(function () {
                        var id = $('select[name="templateQuestion"] option:selected').val();

                        methode.templateDelete(id, 1);
                    });

                    $('input[name="templateQuizEdit"]').click(function () {
                        var id = $('select[name="templateQuiz"] option:selected').val();
                        var text = $('input[name="templateQuizEditText"]').val();

                        methode.templateEdit(id, text, 0);
                    });

                    $('input[name="templateQuestionEdit"]').click(function () {
                        var id = $('select[name="templateQuestion"] option:selected').val();
                        var text = $('input[name="templateQuestionEditText"]').val();

                        methode.templateEdit(id, text, 1);
                    });
                };

                init();
            },

            questionEdit: function () {
                var methode = this;
                var filter = $.noop();

                var elements = {
                    answerChildren: $('.answer_felder > div'),
                    pointsModus: $('input[name="answerPointsActivated"]'),
                    gPoints: $('input[name="points"]')
                };

                methode = {
                    generateArrayIndex: function () {
                        var type = $('input[name="answerType"]:checked').val();
                        type = (type == 'single' || type == 'multiple') ? 'classic_answer' : type;

                        $('.answerList').each(function () {
                            var currentType = $(this).parent().attr('class');

                            $(this).children().each(function (i, v) {
                                $(this).find('[name^="answerData"]').each(function () {
                                    var name = this.name;
                                    var x = name.search(/\](\[\w+\])+$/);
                                    var n = (type == currentType) ? i : 'none';

                                    if (x > 0) {
                                        this.name = 'answerData[' + n + name.substring(x, name.length);

                                    }
                                });
                            });
                        });
                    },

                    globalValidate: function () {
                        if (global.isEmpty(global.getMceContent('question'))) {
                            alert(wpAdvQuizLocalize.no_question_msg);

                            return false;
                        }

                        if (!elements.pointsModus.is(':checked')) {
                            var p = elements.gPoints.val();

                            if (!global.isNumber(p) || p < 1) {
                                alert(wpAdvQuizLocalize.no_nummber_points);

                                return false;
                            }
                        } else {
                            if ($('input[name="answerType"]:checked').val() == 'free_answer') {
                                alert(wpAdvQuizLocalize.dif_points);
                                return false;
                            }
                        }

                        if (filter() === false)
                            return false;

                        return true;
                    },

                    answerRemove: function () {
                        var li = $(this).parent();

                        if (li.parent().children().length < 2)
                            return false;

                        li.remove();

                        return false;
                    },

                    addCategory: function () {
                        var name = $.trim($('input[name="categoryAdd"]').val());

                        if (global.isEmpty(name)) {
                            return;
                        }

                        var data = {
                            categoryName: name
                        };

                        global.ajaxPost('categoryAdd', data, function (json) {
                            if (json.err) {
                                $('#categoryMsgBox').text(json.err).show('fast').delay(2000).hide('fast');
                                return;
                            }

                            var $option = $(document.createElement('option'))
                                .val(json.categoryId)
                                .text(json.categoryName)
                                .attr('selected', 'selected');

                            $('select[name="category"]').append($option).change();

                        });
                    },

                    addMediaClick: function () {
                        if (typeof tb_show != "function")
                            return false;

                        var closest = $(this).closest('li');
                        var htmlCheck = closest.find('input[name="answerData[][html]"]:eq(0)');
                        var field = closest.find('.wpAdvQuiz_text:eq(0)');

                        window.org_send_to_editor = window.send_to_editor;
                        var org_tb_remove = tb_remove;

                        window.send_to_editor = function (html) {
                            var img = $('img', html)[0].outerHTML;

                            field.val(field.val() + img);
                            htmlCheck.attr('checked', true);

                            tb_remove();

                            window.send_to_editor = window.org_send_to_editor;
                        };

                        window.tb_remove = function () {
                            window.send_to_editor = window.org_send_to_editor;
                            tb_remove = org_tb_remove;

                            tb_remove();
                        };

                        tb_show('', 'media-upload.php?type=image&TB_iframe=true');
                    }
                };

                var validate = {
                    classic_answer: function () {
                        var findText = 0;
                        var findCorrect = 0;
                        var findPoints = 0;

                        $('.classic_answer .answerList').children().each(function () {
                            var t = $(this);

                            if (!global.isEmpty(t.find('textarea[name="answerData[][answer]"]').val())) {
                                findText++;

                                if (t.find('input[name="answerData[][correct]"]:checked').length) {
                                    findCorrect++;
                                }

                                var p = t.find('input[name="answerData[][points]"]').val();

                                if (global.isNumber(p) && p >= 0) {
                                    findPoints++;
                                }
                            }
                        });

                        if (!findText) {
                            alert(wpAdvQuizLocalize.no_answer_msg);
                            return false;
                        }

                        if (!findCorrect && !($('input[name="disableCorrect"]').is(':checked')
                            && $('input[name="answerPointsDiffModusActivated"]').is(':checked')
                            && $('input[name="answerPointsActivated"]').is(':checked')
                            && $('input[name="answerType"]:checked').val() == 'single')) {
                            alert(wpAdvQuizLocalize.no_correct_msg);
                            return false;
                        }

                        if (findPoints != findText && elements.pointsModus.is(':checked')) {
                            alert(wpAdvQuizLocalize.no_nummber_points_new);
                            return false;
                        }

                        return true;
                    },

                    free_answer: function () {
                        if (global.isEmpty($('.free_answer textarea[name="answerData[][answer]"]').val())) {
                            alert(wpAdvQuizLocalize.no_answer_msg);
                            return false;
                        }

                        return true;
                    },

                    cloze_answer: function () {
                        if (global.isEmpty(global.getMceContent('cloze'))) {
                            alert(wpAdvQuizLocalize.no_answer_msg);
                            return false;
                        }

                        return true;
                    },

                    sort_answer: function () {
                        var findText = 0;
                        var findPoints = 0;

                        $('.sort_answer .answerList').children().each(function () {
                            var t = $(this);

                            if (!global.isEmpty(t.find('textarea[name="answerData[][answer]"]').val())) {
                                findText++;

                                var p = t.find('input[name="answerData[][points]"]').val();

                                if (global.isNumber(p) && p >= 0) {
                                    findPoints++;
                                }
                            }
                        });

                        if (!findText) {
                            alert(wpAdvQuizLocalize.no_answer_msg);
                            return false;
                        }

                        if (findPoints != findText && elements.pointsModus.is(':checked')) {
                            alert(wpAdvQuizLocalize.no_nummber_points_new);
                            return false;
                        }

                        return true;
                    },

                    matrix_sort_answer: function () {
                        var findText = 0;
                        var findPoints = 0;
                        var sortString = true;
                        var menge = 0;

                        $('.matrix_sort_answer .answerList').children().each(function () {
                            var t = $(this);
                            var p = t.find('input[name="answerData[][points]"]').val();

                            if (!global.isEmpty(t.find('textarea[name="answerData[][answer]"]').val())) {
                                findText++;
                                menge++;

                                if (global.isEmpty(t.find('textarea[name="answerData[][sort_string]"]').val())) {
                                    sortString = false;
                                }

                                if (global.isNumber(p) && p >= 0) {
                                    findPoints++;
                                }
                            } else {
                                if (!global.isEmpty(t.find('textarea[name="answerData[][sort_string]"]').val())) {
                                    menge++;

                                    if (global.isNumber(p) && p >= 0) {
                                        findPoints++;
                                    }
                                }
                            }
                        });

                        if (!findText) {
                            alert(wpAdvQuizLocalize.no_answer_msg);
                            return false;
                        }

                        if (!sortString) {
                            alert(wpAdvQuizLocalize.no_sort_element_criterion);
                            return false;
                        }

                        if (findPoints != menge && elements.pointsModus.is(':checked')) {
                            alert(wpAdvQuizLocalize.no_nummber_points_new);
                            return false;
                        }

                        return true;
                    },

                    assessment_answer: function () {
                        if (global.isEmpty(global.getMceContent('assessment'))) {
                            alert(wpAdvQuizLocalize.no_answer_msg);
                            return false;
                        }

                        return true;
                    }
                };

                var formListener = function () {
                    $('#wpAdvQuiz_tip').change(function () {
                        global.displayChecked(this, $('#wpAdvQuiz_tipBox'));
                    }).change();

                    $('#wpAdvQuiz_correctSameText').change(function () {
                        global.displayChecked(this, $('#wpAdvQuiz_incorrectMassageBox'), true);
                    }).change();

                    $('input[name="answerType"]').click(function () {
                        elements.answerChildren.hide();
                        var v = this.value;

                        if (v == 'single') {
                            $('#singleChoiceOptions').show();
                            $('input[name="disableCorrect"]').change();
                        } else {
                            $('#singleChoiceOptions').hide();
                            $('.classic_answer .wpAdvQuiz_classCorrect').parent().parent().show();
                        }

                        if (v == 'single' || v == 'multiple') {
                            var type = (v == 'single') ? 'radio' : 'checkbox';
                            v = 'classic_answer';

                            $('.wpAdvQuiz_classCorrect').each(function () {
                                $("<input type=" + type + " />")
                                    .attr({
                                        name: this.name,
                                        value: this.value,
                                        checked: this.checked
                                    })
                                    .addClass('wpAdvQuiz_classCorrect wpAdvQuiz_checkbox')
                                    .insertBefore(this);
                            }).remove();
                        }

                        filter = (validate[v] != undefined) ? validate[v] : $.noop();

                        $('.' + v).show();
                    });

                    $('input[name="answerType"]:checked').click();

                    $('.deleteAnswer').click(methode.answerRemove);

                    $('.addAnswer').click(function () {
                        var ul = $(this).siblings('ul');
                        var clone = ul.find('li:eq(0)').clone();

                        clone.find('.wpAdvQuiz_checkbox').removeAttr('checked');
                        clone.find('.wpAdvQuiz_text').val('');
                        clone.find('.wpAdvQuiz_points').val(1);
                        clone.find('.deleteAnswer').click(methode.answerRemove);
                        clone.find('.addMedia').click(methode.addMediaClick);

                        clone.appendTo(ul);

                        return false;
                    });

                    $('.sort_answer ul, .classic_answer ul, .matrix_sort_answer ul').sortable({
                        handle: '.wpAdvQuiz_move'
                    });

                    $('#saveQuestion').click(function () {
                        if (!methode.globalValidate()) {
                            return false;
                        }

                        methode.generateArrayIndex();

                        return true;
                    });

                    $(elements.pointsModus).change(function () {
                        global.displayChecked(this, $('.wpAdvQuiz_answerPoints'));
                        global.displayChecked(this, $('#wpAdvQuiz_showPointsBox'));
                        global.displayChecked(this, elements.gPoints, false, true);
                        global.displayChecked(this, $('input[name="answerPointsDiffModusActivated"]'), true, true);

                        if (this.checked) {
                            $('input[name="answerPointsDiffModusActivated"]').change();
                            $('input[name="disableCorrect"]').change();
                        } else {
                            $('.classic_answer .wpAdvQuiz_classCorrect').parent().parent().show();
                            $('input[name="disableCorrect"]').attr('disabled', 'disabled');
                        }
                    }).change();

                    $('select[name="category"]').change(function () {
                        var $this = $(this);
                        var box = $('#categoryAddBox').hide();

                        if ($this.val() == "-1") {
                            box.show();
                        }

                    }).change();

                    $('#categoryAddBtn').click(function () {
                        methode.addCategory();
                    });

                    $('.addMedia').click(methode.addMediaClick);

                    $('input[name="answerPointsDiffModusActivated"]').change(function () {
                        global.displayChecked(this, $('input[name="disableCorrect"]'), true, true);

                        if (this.checked)
                            $('input[name="disableCorrect"]').change();
                        else
                            $('.classic_answer .wpAdvQuiz_classCorrect').parent().parent().show();
                    }).change();

                    $('input[name="disableCorrect"]').change(function () {
                        global.displayChecked(this, $('.classic_answer .wpAdvQuiz_classCorrect').parent().parent(), true);
                    }).change();

                    $('#clickPointDia').click(function () {
                        $('.pointDia').toggle('fast');

                        return false;
                    });

                    $('input[name="template"]').click(function (e) {
                        if ($('select[name="templateSaveList"]').val() == '0') {
                            if (global.isEmpty($('input[name="templateName"]').val())) {
                                alert(wpAdvQuizLocalize.temploate_no_name);

                                e.preventDefault();
                                return false;
                            }
                        }

                        methode.generateArrayIndex();
                    });

                    $('select[name="templateSaveList"]').change(function () {
                        var $templateName = $('input[name="templateName"]');

                        if ($(this).val() == '0') {
                            $templateName.show();
                        } else {
                            $templateName.hide();
                        }
                    }).change();
                };

                var init = function () {
                    elements.answerChildren.hide();

                    formListener();
                };

                init();
            },

            statistic: function () {

                var methode = this;

                var quizId = $('#quizId').val();

                var currentTab = 'users';

                var elements = {
                    currentPage: $('#wpAdvQuiz_currentPage'),
                    pageLeft: $('#wpAdvQuiz_pageLeft'),
                    pageRight: $('#wpAdvQuiz_pageRight'),
                    testSelect: $('#testSelect')

                };

                methode = {
                    loadStatistic: function (userId, callback) {
                        var data = {
                            userId: userId
                        };

                        global.ajaxPost('statisticLoad', data, function (json) {

                        });
                    },

                    loadUsersStatistic: function () {
                        //var userId = $('#userSelect').val();
                        //
                        //var data = {
                        //    userId: userId,
                        //    quizId: quizId,
                        //    testId: $('#testSelect').val()
                        //};
                        //
                        //methode.toggleLoadBox(false);
                        //
                        //global.ajaxPost('statisticLoad', data, function (json) {
                        //    $.each(json.question, function () {
                        //        var $tr = $('#wpAdvQuiz_tr_' + this.questionId);
                        //
                        //        methode.setStatisticData($tr, this);
                        //    });
                        //
                        //    $.each(json.category, function (i, v) {
                        //        var $tr = $('#wpAdvQuiz_ctr_' + i);
                        //
                        //        methode.setStatisticData($tr, v);
                        //    });
                        //
                        //    $('#testSelect option:gt(0)').remove();
                        //    var $testSelect = $('#testSelect');
                        //
                        //    $.each(json.tests, function () {
                        //        var $option = $(document.createElement('option'));
                        //
                        //        $option.val(this.id);
                        //        $option.text(this.date);
                        //
                        //        if (json.testId == this.id)
                        //            $option.attr('selected', true);
                        //
                        //        $testSelect.append($option);
                        //    });
                        //
                        //    methode.parseFormData(json.formData);
                        //
                        //    methode.toggleLoadBox(true);
                        //});
                    },

                    loadUsersStatistic_: function (userId, testId) {

                        var data = {
                            userId: userId,
                            quizId: quizId,
                            testId: testId
                        };

                        methode.toggleLoadBox(false);

                        global.ajaxPost('statisticLoad', data, function (json) {
                            $.each(json.question, function () {
                                var $tr = $('#wpAdvQuiz_tr_' + this.questionId);

                                methode.setStatisticData($tr, this);
                            });

                            $.each(json.category, function (i, v) {
                                var $tr = $('#wpAdvQuiz_ctr_' + i);

                                methode.setStatisticData($tr, v);
                            });

                            $('#testSelect option:gt(0)').remove();
                            var $testSelect = $('#testSelect');

                            $.each(json.tests, function () {
                                var $option = $(document.createElement('option'));

                                $option.val(this.id);
                                $option.text(this.date);

                                if (json.testId == this.id)
                                    $option.attr('selected', true);

                                $testSelect.append($option);
                            });

                            methode.parseFormData(json.formData);

                            $('#userSelect').val(userId);
                            $('#testSelect').val(testId);

                            methode.toggleLoadBox(true);
                        });
                    },

                    parseFormData: function (data) {
                        var $formBox = $('#wpAdvQuiz_form_box');

                        if (data == null) {
                            $formBox.hide();
                            return;
                        }

                        $.each(data, function (i, v) {
                            $('#form_id_' + i).text(v);
                        });

                        $formBox.show();
                    },

                    setStatisticData: function ($o, v) {
                        $o.find('.wpAdvQuiz_cCorrect').text(v.correct);
                        $o.find('.wpAdvQuiz_cIncorrect').text(v.incorrect);
                        $o.find('.wpAdvQuiz_cTip').text(v.hint);
                        $o.find('.wpAdvQuiz_cPoints').text(v.points);
                        $o.find('.wpAdvQuiz_cResult').text(v.result);
                        $o.find('.wpAdvQuiz_cTime').text(v.questionTime);
                        $o.find('.wpAdvQuiz_cCreateTime').text(v.date);
                    },

                    toggleLoadBox: function (show) {
                        var $loadBox = $('#wpAdvQuiz_loadData');
                        var $content = $('#wpAdvQuiz_content');

                        if (show) {
                            $loadBox.hide();
                            $content.show();
                        } else {
                            $content.hide();
                            $loadBox.show();
                        }
                    },

                    reset: function (type) {
                        var userId = $('#userSelect').val();

                        if (!confirm(wpAdvQuizLocalize.reset_statistics_msg)) {
                            return;
                        }

                        var data = {
                            quizId: quizId,
                            userId: userId,
                            testId: elements.testSelect.val(),
                            type: type
                        };

                        methode.toggleLoadBox(false);

                        global.ajaxPost('statisticReset', data, function () {
                            methode.loadUsersStatistic();
                        });
                    },

                    loadStatisticOverview: function (nav) {

                        var data = {
                            quizId: quizId,
                            pageLimit: $('#wpAdvQuiz_pageLimit').val(),
                            onlyCompleted: Number($('#wpAdvQuiz_onlyCompleted').is(':checked')),
                            page: elements.currentPage.val(),
                            nav: Number(nav)
                        };

                        methode.toggleLoadBox(false);

                        global.ajaxPost('statisticLoadOverview', data, function (json) {
                            var $body = $('#wpAdvQuiz_statistics_overview_data');
                            var $tr = $body.children();
                            var $c = $tr.first().clone();

                            $tr.slice(1).remove();

                            $.each(json.items, function () {
                                var clone = $c.clone();

                                methode.setStatisticData(clone, this);

                                clone.find('a').text(this.userName).data('userId', this.userId).click(function () {
                                    $('#userSelect').val($(this).data('userId'));

                                    $('#wpAdvQuiz_typeUser').click();

                                    return false;
                                });

                                clone.show().appendTo($body);
                            });

                            $c.remove();

                            methode.toggleLoadBox(true);

                            if (json.page != undefined)
                                methode.handleNav(json.page);
                        });

                    },

                    handleNav: function (nav) {
                        var $p = $('#wpAdvQuiz_currentPage').empty();

                        for (var i = 1; i <= nav; i++) {
                            $(document.createElement('option'))
                                .val(i)
                                .text(i)
                                .appendTo($p);
                        }

                        methode.checkNavBar();
                    },

                    checkNavBar: function () {
                        var n = elements.currentPage.val();

                        if (n == 1) {
                            elements.pageLeft.hide();
                        } else {
                            elements.pageLeft.show();
                        }

                        if (n == elements.currentPage.children().length) {
                            elements.pageRight.hide();
                        } else {
                            elements.pageRight.show();
                        }
                    },

                    refresh: function () {
                        if (currentTab == 'users') {
                            methode.loadUsersStatistic();
                        } else if (currentTab == 'formOverview') {
                            methode.loadFormsOverview(true);
                        } else {
                            methode.loadStatisticOverview(true);
                        }
                    },

                    loadFormsOverview: function (nav) {
                        var data = {
                            quizId: quizId,
                            pageLimit: $('#wpAdvQuiz_fromPageLimit').val(),
                            onlyUser: $('#wpAdvQuiz_formUser').val(),
                            page: $('#wpAdvQuiz_formCurrentPage').val(),
                            nav: Number(nav)
                        };

                        methode.toggleLoadBox(false);

                        global.ajaxPost('statisticLoadFormOverview', data, function (json) {
                            var $body = $('#wpAdvQuiz_statistics_form_data');
                            var $tr = $body.children();
                            var $c = $tr.first().clone();

                            $tr.slice(1).remove();

                            $.each(json.items, function () {
                                var clone = $c.clone();

                                methode.setStatisticData(clone, this);

                                clone.find('a').text(this.userName).data('userId', this.userId).data('testId', this.testId).click(function () {
                                    methode.switchTabOnLoad('users');
                                    methode.loadUsersStatistic_($(this).data('userId'), $(this).data('testId'));

                                    return false;
                                });

                                clone.show().appendTo($body);
                            });

                            $c.remove();

                            methode.toggleLoadBox(true);

                            if (json.page != undefined)
                                methode.handleFormNav(json.page);
                        });
                    },

                    handleFormNav: function (nav) {
                        var $p = $('#wpAdvQuiz_formCurrentPage').empty();

                        for (var i = 1; i <= nav; i++) {
                            $(document.createElement('option'))
                                .val(i)
                                .text(i)
                                .appendTo($p);
                        }

                        methode.checkFormNavBar();
                    },

                    checkFormNavBar: function () {
                        var n = $('#wpAdvQuiz_formCurrentPage').val();

                        if (n == 1) {
                            $('#wpAdvQuiz_formPageLeft').hide();
                        } else {
                            $('#wpAdvQuiz_formPageLeft').show();
                        }

                        if (n == $('#wpAdvQuiz_formCurrentPage').children().length) {
                            $('#wpAdvQuiz_formPageRight').hide();
                        } else {
                            $('#wpAdvQuiz_formPageRight').show();
                        }
                    },

                    switchTabOnLoad: function (name) {
                        $('.wpAdvQuiz_tab').removeClass('button-primary').addClass('button-secondary');
                        $('.wpAdvQuiz_tabContent').hide();

                        var $this = $('#wpAdvQuiz_typeOverview');

                        if (name == 'users') {
                            currentTab = 'users';
                            $('#wpAdvQuiz_tabUsers').show();
                            $this = $('#wpAdvQuiz_typeUser');
                        } else if (name == 'formOverview') {
                            currentTab = 'formOverview';
                            $('#wpAdvQuiz_tabFormOverview').show();
                            $this = $('#wpAdvQuiz_typeForm');
                        } else {
                            currentTab = 'overview';
                            $('#wpAdvQuiz_tabOverview').show();
                        }

                        $this.removeClass('button-secondary').addClass('button-primary');
                    }
                };

                var init = function () {

                    $('#userSelect, #testSelect').change(function () {
                        methode.loadUsersStatistic();
                    });

                    $('.wpAdvQuiz_update').click(function () {
                        methode.refresh();
                    });

                    $('#wpAdvQuiz_reset').click(function () {
                        methode.reset(0);
                    });

                    $('#wpAdvQuiz_resetUser').click(function () {
                        methode.reset(1);
                    });

                    $('.wpAdvQuiz_resetComplete').click(function () {
                        methode.reset(2);
                    });

                    $('.wpAdvQuiz_tab').click(function () {
                        var $this = $(this);

                        $('.wpAdvQuiz_tab').removeClass('button-primary').addClass('button-secondary');
                        $this.removeClass('button-secondary').addClass('button-primary');
                        $('.wpAdvQuiz_tabContent').hide();

                        if ($this.attr('id') == 'wpAdvQuiz_typeUser') {
                            currentTab = 'users';
                            $('#wpAdvQuiz_tabUsers').show();
                            methode.loadUsersStatistic();
                        } else if ($this.attr('id') == 'wpAdvQuiz_typeForm') {
                            currentTab = 'formOverview';
                            $('#wpAdvQuiz_tabFormOverview').show();
                            methode.loadFormsOverview(true);
                        } else {
                            currentTab = 'overview';
                            $('#wpAdvQuiz_tabOverview').show();
                            methode.loadStatisticOverview(true);
                        }

                        return false;
                    });

                    $('#wpAdvQuiz_onlyCompleted').change(function () {
                        elements.currentPage.val(1);
                        methode.loadStatisticOverview(true);
                    });

                    $('#wpAdvQuiz_pageLimit').change(function () {
                        elements.currentPage.val(1);
                        methode.loadStatisticOverview(true);
                    });

                    elements.pageLeft.click(function () {
                        elements.currentPage.val(Number(elements.currentPage.val()) - 1);
                        methode.loadStatisticOverview(false);
                        methode.checkNavBar();
                    });

                    elements.pageRight.click(function () {
                        elements.currentPage.val(Number(elements.currentPage.val()) + 1);
                        methode.loadStatisticOverview(false);
                        methode.checkNavBar();
                    });

                    elements.currentPage.change(function () {
                        methode.loadStatisticOverview(false);
                        methode.checkNavBar();
                    });

                    $('#wpAdvQuiz_formUser, #wpAdvQuiz_fromPageLimit').change(function () {
                        $('#wpAdvQuiz_formCurrentPage').val(1);
                        methode.loadFormsOverview(true);
                    });

                    $('#wpAdvQuiz_formPageLeft').click(function () {
                        $('#wpAdvQuiz_formCurrentPage').val(Number(elements.currentPage.val()) - 1);
                        methode.loadFormsOverview(false);
                        methode.checkFormNavBar();
                    });

                    $('#wpAdvQuiz_formPageRight').click(function () {
                        $('#wpAdvQuiz_formCurrentPage').val(Number(elements.currentPage.val()) + 1);
                        methode.loadFormsOverview(false);
                        methode.checkFormNavBar();
                    });

                    $('#wpAdvQuiz_formCurrentPage').change(function () {
                        methode.loadFormsOverview(false);
                        methode.checkFormNavBar();
                    });

                    methode.loadUsersStatistic();
                };

                init();
            },

            statisticNew: function () {
                var quizId = $('#quizId').val();
                var historyNavigator = null;
                var overviewNavigator = null;

                var historyFilter = {
                    data: {
                        quizId: quizId,
                        users: -1,
                        pageLimit: 100,
                        dateFrom: 0,
                        dateTo: 0,
                        generateNav: 0
                    },

                    changeFilter: function () {
                        var getTime = function (p) {
                            var date = p.datepicker('getDate');

                            return date === null ? 0 : date.getTime() / 1000;
                        };

                        $.extend(this.data, {
                            users: $('#wpAdvQuiz_historyUser').val(),
                            pageLimit: $('#wpAdvQuiz_historyPageLimit').val(),
                            dateFrom: getTime($('#datepickerFrom')),
                            dateTo: getTime($('#datepickerTo')),
                            generateNav: 1
                        });

                        return this.data;
                    }
                };

                var overviewFilter = {
                    data: {
                        pageLimit: 100,
                        onlyCompleted: 0,
                        generateNav: 0,
                        quizId: quizId
                    },

                    changeFilter: function () {
                        $.extend(this.data, {
                            pageLimit: $('#wpAdvQuiz_overviewPageLimit').val(),
                            onlyCompleted: Number($('#wpAdvQuiz_overviewOnlyCompleted').is(':checked')),
                            generateNav: 1
                        });
                    }
                };

                var deleteMethode = {
                    deleteUserStatistic: function (refId, userId) {
                        if (!confirm(wpAdvQuizLocalize.reset_statistics_msg))
                            return false;

                        var data = {
                            refId: refId,
                            userId: userId,
                            quizId: quizId,
                            type: 0
                        };

                        global.ajaxPost('statisticResetNew', data, function () {
                            $('#wpAdvQuiz_user_overlay').hide();

                            historyFilter.changeFilter();
                            methode.loadHistoryAjax();

                            overviewFilter.changeFilter();
                            methode.loadOverviewAjax();

                        });
                    },

                    deleteAll: function () {
                        if (!confirm(wpAdvQuizLocalize.reset_statistics_msg))
                            return false;

                        var data = {
                            quizId: quizId,
                            type: 1
                        };

                        global.ajaxPost('statisticResetNew', data, function () {
                            historyFilter.changeFilter();
                            methode.loadHistoryAjax();

                            overviewFilter.changeFilter();
                            methode.loadOverviewAjax();
                        });
                    }
                };

                var methode = {
                    loadHistoryAjax: function () {
                        var self = this;
                        var data = $.extend({
                            page: historyFilter.data.generateNav ? 1 : historyNavigator.getCurrentPage()
                        }, historyFilter.data);

                        methode.loadBox(true);
                        var content = $('#wpAdvQuiz_historyLoadContext').hide();

                        global.ajaxPost('statisticLoadHistory', data, function (json) {
                            content.html(json.html).show();

                            if (json.navi)
                                historyNavigator.setNumPage(json.navi);

                            historyFilter.data.generateNav = 0;

                            content.find('.user_statistic').click(function () {
                                methode.loadUserAjax(0, $(this).data('ref_id'), false);

                                return false;
                            });

                            content.find('.wpAdvQuiz_delete').click(function () {
                                deleteMethode.deleteUserStatistic($(this).parents('tr').find('.user_statistic').data('ref_id'), 0);

                                return false;
                            });

                            content.find('.wpAdvQuiz_export').click(function () {
                                self.setExportBoxAction($(this).data('baseurl'));
                                self.showWpAdvQuizModalBox('', 'wpAdvQuiz_statsticExportList_box');

                                return false;
                            });


                            methode.loadBox(false);
                        });

                    },

                    loadUserAjax: function (userId, refId, avg) {
                        $('#wpAdvQuiz_user_overlay, #wpAdvQuiz_loadUserData').show();

                        var self = this;
                        var content = $('#wpAdvQuiz_user_content').hide();

                        var data = {
                            quizId: quizId,
                            userId: userId,
                            refId: refId,
                            avg: Number(avg)
                        };

                        global.ajaxPost('statisticLoadUser', data, function (json) {
                            content.html(json.html);

                            content.find('.wpAdvQuiz_update').click(function () {
                                methode.loadUserAjax(userId, refId, avg);

                                return false;
                            });

                            content.find('.wpAdvQuiz_export').click(function () {
                                self.setExportBoxAction($(this).data('baseurl'));

                                $('#wpAdvQuiz_overlay_close').click();

                                self.showWpAdvQuizModalBox('', 'wpAdvQuiz_statsticExportList_box');

                                return false;
                            });

                            content.find('#wpAdvQuiz_resetUserStatistic').click(function () {
                                deleteMethode.deleteUserStatistic(refId, userId);
                            });

                            content.find('.statistic_data').click(function () {
                                $(this).parents('tr').next().toggle('fast');

                                return false;
                            });

                            $('#wpAdvQuiz_loadUserData').hide();
                            content.show();
                        });

                    },

                    loadBox: function (show, contain) {
                        if (show)
                            $('#wpAdvQuiz_loadDataHistory').show();
                        else
                            $('#wpAdvQuiz_loadDataHistory').hide();

                    },

                    loadOverviewAjax: function () {
                        var data = $.extend({
                            page: overviewFilter.data.generateNav ? 1 : overviewNavigator.getCurrentPage()
                        }, overviewFilter.data);

                        $('#wpAdvQuiz_loadDataOverview').show();

                        var self = this;
                        var content = $('#wpAdvQuiz_overviewLoadContext').hide();

                        global.ajaxPost('statisticLoadOverviewNew', data, function (json) {
                            content.html(json.html).show();

                            if (json.navi)
                                overviewNavigator.setNumPage(json.navi);

                            overviewFilter.data.generateNav = 0;

                            content.find('.user_statistic').click(function () {
                                methode.loadUserAjax($(this).data('user_id'), 0, true);

                                return false;
                            });

                            content.find('.wpAdvQuiz_delete').click(function () {
                                deleteMethode.deleteUserStatistic(0, $(this).parents('tr').find('.user_statistic').data('user_id'));

                                return false;
                            });

                            content.find('.wpAdvQuiz_export').click(function () {
                                self.setExportBoxAction($(this).data('baseurl'));
                                self.showWpAdvQuizModalBox('', 'wpAdvQuiz_statsticExportList_box');

                                return false;
                            });

                            $('#wpAdvQuiz_loadDataOverview').hide();
                        });
                    },

                    showWpAdvQuizModalBox: function(title, id) {
                        var width = Math.min($('.wpAdvQuiz_tabContent').width() - 50, 600);
                        var a = '#TB_inline?width=' + width + '&inlineId=' + id;

                        tb_show(title, a, false);
                    },

                    setExportBoxAction: function(action) {
                        var $box = $('#wpAdvQuiz_statsticExportList_box');
                        $box.find('form').attr('action', action);
                    },

                    exportHistory: function() {
                        var data = $.extend({
                            page: historyNavigator.getCurrentPage()
                        }, historyFilter.data);

                        var url = 'admin.php?page=wpAdvQuiz&module=statistic_export&action=history_export&noheader=true' +
                            '&_page=' + data.page +
                            '&quiz_id=' + data.quizId +
                            '&users=' + data.users +
                            '&page_limit=' + data.pageLimit +
                            '&date_from=' + data.dateFrom +
                            '&date_to=' + data.dateTo;

                        this.setExportBoxAction(url);
                        this.showWpAdvQuizModalBox('', 'wpAdvQuiz_statsticExportList_box');
                    },

                    exportOverview: function() {
                        var data = $.extend({
                            page: historyNavigator.getCurrentPage()
                        }, overviewFilter.data);

                        var url = 'admin.php?page=wpAdvQuiz&module=statistic_export&action=overview_export&noheader=true' +
                            '&_page=' + data.page +
                            '&quiz_id=' + data.quizId +
                            '&only_completed=' + data.onlyCompleted +
                            '&page_limit=' + data.pageLimit;

                        this.setExportBoxAction(url);
                        this.showWpAdvQuizModalBox('', 'wpAdvQuiz_statsticExportList_box');
                    }
                };

                var init = function () {
                    historyNavigator = new Navigator($('#historyNavigation'), {
                        onChange: function () {
                            methode.loadHistoryAjax();
                        }
                    });

                    overviewNavigator = new Navigator($('#overviewNavigation'), {
                        onChange: function () {
                            methode.loadOverviewAjax();
                        }
                    });

                    $('#datepickerFrom').datepicker({
                        closeText: wpAdvQuizLocalize.closeText,
                        currentText: wpAdvQuizLocalize.currentText,
                        monthNames: wpAdvQuizLocalize.monthNames,
                        monthNamesShort: wpAdvQuizLocalize.monthNamesShort,
                        dayNames: wpAdvQuizLocalize.dayNames,
                        dayNamesShort: wpAdvQuizLocalize.dayNamesShort,
                        dayNamesMin: wpAdvQuizLocalize.dayNamesMin,
                        dateFormat: wpAdvQuizLocalize.dateFormat,
                        firstDay: wpAdvQuizLocalize.firstDay,

                        changeMonth: true,
                        onClose: function (selectedDate) {
                            $('#datepickerTo').datepicker('option', 'minDate', selectedDate);
                        }
                    });

                    $('#datepickerTo').datepicker({
                        closeText: wpAdvQuizLocalize.closeText,
                        currentText: wpAdvQuizLocalize.currentText,
                        monthNames: wpAdvQuizLocalize.monthNames,
                        monthNamesShort: wpAdvQuizLocalize.monthNamesShort,
                        dayNames: wpAdvQuizLocalize.dayNames,
                        dayNamesShort: wpAdvQuizLocalize.dayNamesShort,
                        dayNamesMin: wpAdvQuizLocalize.dayNamesMin,
                        dateFormat: wpAdvQuizLocalize.dateFormat,
                        firstDay: wpAdvQuizLocalize.firstDay,

                        changeMonth: true,
                        onClose: function (selectedDate) {
                            $('#datepickerFrom').datepicker('option', 'maxDate', selectedDate);
                        }
                    });

                    $('#filter').click(function () {
                        historyFilter.changeFilter();
                        methode.loadHistoryAjax();
                    });

                    $('#wpAdvQuiz_overlay_close').click(function () {
                        $('#wpAdvQuiz_user_overlay').hide();
                    });

                    $('#wpAdvQuiz_tabHistory .wpAdvQuiz_update').click(function () {
                        historyFilter.changeFilter();
                        methode.loadHistoryAjax();

                        return false;
                    });

                    $('#wpAdvQuiz_tabHistory .wpAdvQuiz_export').click(function () {
                        historyFilter.changeFilter();
                        methode.exportHistory();

                        return false;
                    });

                    $('#wpAdvQuiz_tabOverview .wpAdvQuiz_update').click(function () {
                        overviewFilter.changeFilter();
                        methode.loadOverviewAjax();

                        return false;
                    });

                    $('#wpAdvQuiz_tabOverview .wpAdvQuiz_export').click(function () {
                        historyFilter.changeFilter();
                        methode.exportOverview();

                        return false;
                    });

                    $('.wpAdvQuiz_resetComplete').click(function () {
                        deleteMethode.deleteAll();

                        return false;
                    });

                    $('#overviewFilter').click(function () {
                        overviewFilter.changeFilter();
                        methode.loadOverviewAjax();
                    });

                    historyFilter.changeFilter();
                    methode.loadHistoryAjax();

                    overviewFilter.changeFilter();
                    methode.loadOverviewAjax();
                };

                init();
            }
        };

        var init = function () {
            tabWrapper();

            var m = $.noop;

            if ($('.wpAdvQuiz_questionEdit').length) {
                m = module.questionEdit;
            } else if ($('.wpAdvQuiz_globalSettings').length) {
                m = module.gobalSettings;
            } else if ($('.wpAdvQuiz_statistics').length) {
                m = module.statistic;
            } else if ($('.wpAdvQuiz_statisticsNew').length) {
                m = module.statisticNew;
            }

            m();

            $('.wpAdvQuiz_demoImgBox a').mouseover(function (e) {
                var $this = $(this);
                var d = $(document).width();
                var img = $this.siblings().outerWidth(true);

                if (e.pageX + img > d) {
                    var v = d - (e.pageX + img + 30);
                    $(this).next().css('left', v + "px");
                }

                $(this).next().show();

            }).mouseout(function () {
                $(this).next().hide();
            }).click(function () {
                return false;
            });
        };

        init();
    }

    WpAdvQuiz_Admin();

    function Navigator(obj, option) {
        var defaultOption = {
            onChange: null
        };

        var elements = {
            contain: null,
            pageLeft: null,
            pageRight: null,
            currentPage: null
        };

        var checkNavBar = function () {
            var num = elements.currentPage.children().length;
            var cur = Number(elements.currentPage.val());

            elements.pageLeft.hide();
            elements.pageRight.hide();

            if (cur > 1)
                elements.pageLeft.show();

            if ((cur + 1) <= num)
                elements.pageRight.show();
        };

        var init = function () {
            $.extend(elements, {
                contain: obj,
                pageLeft: obj.find('.navigationLeft'),
                pageRight: obj.find('.navigationRight'),
                currentPage: obj.find('.navigationCurrentPage')
            });

            $.extend(defaultOption, option);

            elements.pageLeft.click(function () {
                elements.currentPage.val(Number(elements.currentPage.val()) - 1);
                checkNavBar();

                if (defaultOption.onChange)
                    defaultOption.onChange(elements.currentPage.val());
            });

            elements.pageRight.click(function () {
                elements.currentPage.val(Number(elements.currentPage.val()) + 1);
                checkNavBar();

                if (defaultOption.onChange)
                    defaultOption.onChange(elements.currentPage.val());
            });

            elements.currentPage.change(function () {
                checkNavBar();

                if (defaultOption.onChange)
                    defaultOption.onChange(elements.currentPage.val());
            });
        };

        this.getCurrentPage = function () {
            return elements.currentPage.val();
        }

        this.setNumPage = function (num) {
            elements.currentPage.empty();

            for (var i = 1; i <= num; i++) {
                $(document.createElement('option'))
                    .val(i)
                    .text(i)
                    .appendTo(elements.currentPage);
            }

            checkNavBar();
        }

        init();
    }


    $('.wpAdvQuiz-top-tab-wrapper > a.nav-tab').click(function (e) {
        e.preventDefault();

        var $this = $(this);

        $('.wpAdvQuiz-top-tab-wrapper > a.nav-tab').removeClass('nav-tab-active');
        $this.addClass('nav-tab-active');

        $('.wpAdvQuiz-tab-content').removeClass('wpAdvQuiz-tab-content-active');
        $('#' + $this.data('tab')).addClass('wpAdvQuiz-tab-content-active');

        $(document).trigger({
            type: 'changeTab',
            tabId: '#' + $this.data('tab')
        });

        return false;
    })
});
