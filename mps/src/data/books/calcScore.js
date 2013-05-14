var numQuestions = 4;
var rawScore = 0;
var actualScore = 0;
var question0;
var question1;
var question2;
var question3;
var key0 = 0;
var key1 = 0;
var key2 = 0;
var key3 = 0;
var grade1 = 0;
var grade2 = 0;
var grade3 = 0;
var grade4 = 0;
                      alerta("adasdasd");

function getAnswer()
        {
            
            for (var i=0; i < 2; i++)
            {
               if (document.getElementById("quizForm20").key0b20[i].checked)
               {
                  question0 = document.getElementById("quizForm20").key0b20[i].value;
                  break;
               }
            }
           
            for (var i=0; i < 2; i++)
            {
               if (document.getElementById("quizForm20").key1b20[i].checked)
               {
                  question1 = document.getElementById("quizForm20").key1b20[i].value;
                  break;
               }
            }
           
            for (var i=0; i < 2; i++)
            {
               if (document.getElementById("quizForm20").key2b20[i].checked)
               {
                  question2 = document.getElementById("quizForm20").key2b20[i].value;
                  break;
               }
            }
           
            for (var i=0; i < 2; i++)
            {
               if (document.getElementById("quizForm20").key3b20[i].checked)
               {
                  question3 = document.getElementById("quizForm20").key3b20[i].value;
                  break;
               }
            }
           }
           
        function calcRawScore(){

            if (question0 == key0)
            {
               rawScore++;
               grade1 = 100;
            }

            if (question1 == key1)
            {
               rawScore++;
               grade2 = 100;
            }
            if (question2 == key2)
            {
               rawScore++;
               grade3 = 100;
            }
            if (question3 == key3)
            {
               rawScore++;
               grade4 = 100;
            }
        }
        
        function calcScore2()
        {
           //computeTime();  // the student has stopped here.
           document.getElementById("quizForm20").submitB.disabled = true;
       
           getAnswer();
     
           calcRawScore();
           
           actualScore = Math.round(rawScore / numQuestions * 100);


           document.getElementById("grade_1").value = grade1;   
           document.getElementById("grade_2").value = grade2;   
           document.getElementById("grade_3").value = grade3;   
           document.getElementById("grade_4").value = grade4;   
           document.getElementById("totalgrade").value = actualScore;   
           
           GradeMsg = "La seva qualificació final és de " + actualScore + "%\n\n";
           GradeMsg = GradeMsg + "  - Qualificació primera pregunta: " + grade1 + "\n";
           GradeMsg = GradeMsg + "  - Qualificació segona pregunta:  " + grade2 + "\n";
           GradeMsg = GradeMsg + "  - Qualificació tercera pregunta: " + grade3 + "\n";
           GradeMsg = GradeMsg + "  - Qualificació quarta pregunta:  " + grade4;
           alert(GradeMsg);
           
         exitPageStatus = true;
     
        }
        
