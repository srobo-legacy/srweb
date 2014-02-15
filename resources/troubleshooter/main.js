(function() {
    var data = { };
    var globalRadioName = 0;

    function loadData(url, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", url, true);
        xhr.onreadystatechange = function(e) {
            if (e.target.readyState === 4) {
                try {
                    callback(JSON.parse(e.target.response));
                } catch (e) {
                    // most likely JSON parsing failed
                    // what should we do here?
                    alert("An unexpected error has occured. Please try again later.");
                }
            }
        };

        xhr.send();
    }

    function init() {
        var resetBtnAtTop = document.getElementById("resetbtnattop");
        resetBtnAtTop.onclick = function() {
            window.location.hash = "";
            reset();
        };

        reset();
    }

    function reset() {
        globalRadioId = 0;

        var resetBtnAtTop = document.getElementById("resetbtnattop");
        resetBtnAtTop.style.display = "none";

        var troubleshooter = document.getElementById("troubleshooter");
        troubleshooter.innerHTML = "";

        if (window.location.hash != "") {
            resetBtnAtTop.style.display = "inline";
        }

        var id = window.location.hash.substr(1); // substr to remove # on fragment
        appendQuestionById(troubleshooter, id);
    }

    function answerClicked(radioBtn, parent, answer) {
        parent.innerHTML = "";

        if ("message" in answer) {
            var newMessage = document.createElement('p');
            newMessage.className = "message";
            newMessage.innerHTML = answer.message;
            parent.appendChild(newMessage);
        }

        if ("askiffixed" in answer) {
            var question = data.askiffixed_question;
            question.answers[1].nextquestion = answer.nextquestion;
            appendQuestion(parent, question);
            return;
        }

        if (!("nextquestion" in answer)) {
            var newMessage = document.createElement("p");
            newMessage.className = "message";
            newMessage.textContent = "Thank you for using the SR Troubleshooter.";
            parent.appendChild(newMessage);

            var resetBtnAtTop = document.getElementById("resetbtnattop");
            resetBtnAtTop.style.display = "inline";

            return;
        }

        appendQuestionById(parent, answer.nextquestion);
    }

    function appendQuestionById(parent, id) {
        window.location.hash = "#" + id;
        var question = data.questions[id];
        appendQuestion(parent, question);
    }

    function appendQuestion(parent, question) {
        var newQuestionCont = document.createElement("div");

        var newQuestionDiv = document.createElement("div");
        newQuestionDiv.className = "question";

        var questionParagraph = document.createElement("p");
        questionParagraph.innerHTML = question.question;
        newQuestionDiv.appendChild(questionParagraph);

        var questionForm = document.createElement("form");
        newQuestionDiv.appendChild(questionForm);

        globalRadioName++;

        question.answers.forEach(function(answer) {
            var answerLabel = document.createElement("label");

            var answerRadioBtn = document.createElement("input");
            answerRadioBtn.type = "radio";
            answerRadioBtn.name = globalRadioName;
            answerRadioBtn.onclick = function() {
                answerClicked(answerRadioBtn, newQuestionCont, answer);
            };

            var answerSpan = document.createElement("span");
            answerSpan.textContent = answer.answer;

            answerLabel.appendChild(answerRadioBtn);
            answerLabel.appendChild(answerSpan);
            questionForm.appendChild(answerLabel);
        });

        parent.appendChild(newQuestionDiv);
        parent.appendChild(newQuestionCont);
    }

    loadData("/resources/troubleshooter/data.json", function(d) {
        data = d;
        init();
    });
}());
