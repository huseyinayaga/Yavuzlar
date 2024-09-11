var questions = JSON.parse(localStorage.getItem('questions')) || [];

document.getElementById("questForm").addEventListener("submit",function(event){
    event.preventDefault();
    var questTitle = document.getElementById("quest-title").value;
    var optionOne = document.getElementById("option-one").value;
    var optionTwo = document.getElementById("option-two").value;
    var optionThree = document.getElementById("option-three").value;
    var optionFour = document.getElementById("option-four").value;

    var selectedRadio1 = document.querySelector('input[name= "zorluk" ]:checked');
    var selectedDifficulty = selectedRadio1 ? selectedRadio1.value : "Zorluk secilmemis";

    var selectedRadio2 = document.querySelector('input[name= "Secenek" ]:checked');
    var selectedOption = selectedRadio2 ? selectedRadio2.value : "Şık secilmemis";

    var question ={
        title:questTitle,
        answers: [
            { text: optionOne, correct: selectedOption == 'A' },
            { text: optionTwo, correct: selectedOption == 'B' },
            { text: optionThree, correct: selectedOption == 'C' },
            { text: optionFour, correct: selectedOption == 'D' }
        ],
        difficulty:selectedDifficulty,

    }

    questions.push(question);
    localStorage.setItem('questions', JSON.stringify(questions));

    document.getElementById("questForm").reset();
    document.getElementById("p").textContent = "Soru Basariyla Eklendi";
    

});

function getQuestList(){
    window.location.href='questList.html';
}

function start(){
    window.location.href='start.html';

}

 







