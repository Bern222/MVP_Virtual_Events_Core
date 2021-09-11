// Trivia ----------------------------------------------------------------

function startQuiz() {
	triviaIndex = 0;
	triviaScore = 0;
	$('.trivia-question').hide();
	$('.trivia-nav-previous').addClass('button-disabled');
	$('.trivia-intro-container').hide();
	$('#triviaQuestion' + triviaIndex).show();
	$('.trivia-questions-container').show();

}

function nextQuestion() {
	$('.trivia-question').hide();
	triviaIndex++;

	if(triviaIndex <= triviaData.length) {
		if(triviaIndex > triviaData.length) {
			quizEnded();
		} else {
			$('#triviaQuestion' + triviaIndex).show();
			$('.trivia-nav-previous').removeClass('button-disabled');
		}
	} 

	
}

function previousQuestion() {
	$('.trivia-question').hide();
	triviaIndex--;

	if(triviaIndex >= 0) {
		$('#triviaQuestion' + triviaIndex).show();
		if (triviaIndex == 0) {
			$('.trivia-nav-previous').addClass('button-disabled');
		}
	}

}

function triviaAnswerSelected(questionId, answerId, isCorrect) {
	triviaAnswers[triviaIndex] = {
		questionId: questionId,
		answerId: answerId,
		isCorrect: isCorrect
	};
}

function quizEnded() {
	$('.trivia-intro-container').show();
	$('.trivia-questions-container').hide();
	$('.trivia-intro-text').hide();
	$('.trivia-final-text').show();
	for(var i=0;i<triviaAnswers.length; i++) {
		triviaScore += parseInt(triviaAnswers[i].isCorrect);
	}
	request = $.post("triviaData.php", {
		method: "checkResult",
		// email: currentUser.email
		email: 'benmeden1@gmail.com',
		score: triviaScore,
		
	});
	request.done(function (response, textStatus, jqXHR) {

	});

}

function checkTrivia() {
	request = $.post("triviaData.php", {
		method: "checkResult",
		// email: currentUser.email
		email: 'benmeden1@gmail.com'
	});
	request.done(function (response, textStatus, jqXHR) {
		var triviaResult = JSON.parse(response);

		if(!triviaResult || triviaResult.length == 0) {
			$('.trivia-final-text').hide();
			$('.trivia-intro-text').show();
			$('.trivia-continue-button').show();
			getTrivia();
		} else {
			$('.trivia-final-text').show();
			$('.trivia-intro-text').hide();
			$('.trivia-continue-button').hide();
		}
	});

	request.fail(function(xhr, status, error) {
		console.log('ERROR:', error);
	})

}

function getTrivia() {
	requestQuestions = $.post("triviaData.php", {
		method: "getQuestions"
	});
	requestQuestions.done(function (responseQuestions, textStatus, jqXHR) {
		requestAnswers = $.post("triviaData.php", {
			method: "getAnswers"
		});
		requestAnswers.done(function (responseAnswers, textStatus, jqXHR) {
			var questions = JSON.parse(responseQuestions);	
			var answers = JSON.parse(responseAnswers);	
			
			for(var i=0;i<answers.length;i++) {
				for(var k=0;k<questions.length;k++) {
					if(!questions[k].answers) {
						questions[k].answers = [];
					}
					if(questions[k].id == answers[i].question_id) {
						questions[k].answers.push(answers[i]);
					}
				}
			}
			triviaData = questions;
			buildTriviaHTML();
		});
	});
}

function buildTriviaHTML() {
	for(var i=0;i<triviaData.length;i++) {
		var newQuestion = 	'<div id="triviaQuestion' + i + '" class="trivia-question">';
			newQuestion += 			'<div class="trivia-question-text">' + triviaData[i].title +'</div>';
			newQuestion += 				'<div class="trivia-answer-container">';
		for(var k=0;k<triviaData[i].answers.length;k++) {
			newQuestion +=	'<div id="answer1" class="trivia-answer" onclick="triviaAnswerSelected(\'' + i + '\', \'' + k +'\', \'' + triviaData[i].answers[k].is_correct + '\')">' + triviaData[i].answers[k].title + '</div>';
		}

		newQuestion += 		'</div></div>';

		$('.trivia-questions').append(newQuestion);
	}
}