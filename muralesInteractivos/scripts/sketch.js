let input;
let button;
let leaveNoteButton;
let colorButtons = [];
let previewBox;
let isLeavingNote = false;
let pastelColors = ['#FFB6C1', '#87CEFA', '#98FB98', '#FFD700', '#FFA07A', '#FF69B4', '#00FA9A', '#B0E0E6'];
let messages = [];
let draggingNote = null;
let enlargedNote = null;
let customFont;
let backgroundImage;
let puedeGuardar = false;
let tamanoXCanva = 370;
let tamanoYcanva = 601;
function preload() {
  customFont = loadFont('../assets/DarumadropOne-Regular.ttf');
  backgroundImage = loadImage('../assets/img/fondoCorcho.png');
  backgroundImage2 = loadImage('../assets/img/fondoCorchoLargo.png');

}

function setup() {
  let canvasDiv = document.querySelector("#canvas");
  let theCanvas = createCanvas(tamanoXCanva, tamanoYcanva);
  theCanvas.parent(canvasDiv);
  createInterface();

  // Load existing notes
  existingNotes.forEach(note => {
    messages.push(new Message(note.mensaje, note.posX, note.posY, note.color));
  });

  let initialColor = color('#FFB6C1');
  previewBox = new PreviewBox(width / 2 - 100, height / 2 - 100, initialColor);
  previewBox.hide();
  textFont(customFont);
}

function draw() {
  background(backgroundImage2);

  for (let message of messages) {
    if (message !== enlargedNote) {
      message.display();
    }
  }

  if (enlargedNote) {
    enlargedNote.display(true); // Mostrar la nota agrandada sobre las demás
  }

  if (isLeavingNote) {
    previewBox.display();
  }

  if (draggingNote) {
    draggingNote.updatePosition(mouseX - draggingNote.offsetX, mouseY - draggingNote.offsetY);
  }
}

function createInterface() {
  leaveNoteButton = createButton('¡Dejar Nota!');

  leaveNoteButton.style(`
    background-color: #FF8181;
    color: black;
    padding: 10px 20px;
    border: 1px solid;
    border-radius: 20px;
    cursor: pointer;
    font-family: "Darumadrop One", sans-serif;
    font-size: 16px;
  `);
  leaveNoteButton.mousePressed(() => {
    isLeavingNote = !isLeavingNote;
    toggleInterface(isLeavingNote);
    if (isLeavingNote) {
      showPreview();
    }
  });

  pastelColors.forEach((color, i) => {
    let colorButton = createButton('');
    colorButton.size(30, 30);
    colorButton.style('background-color', color);
    colorButton.mousePressed(() => {
      previewBox.updateColor(color);
    });
    colorButtons.push(colorButton);
  });

  input = createInput();
  input.input(showPreview);

  button = createButton('Enviar');
  button.mousePressed(addMessage);
  button.style(`
    background-color: #FF8181;
    color: black;
    padding: 10px 20px;
    border: 1px solid;
    border-radius: 20px;
    cursor: pointer;
    font-family: "Darumadrop One", sans-serif;
    font-size: 16px;
  `);

  adjustInterfacePositions();

  toggleInterface(false);

  previewBox = new PreviewBox(width / 2 - 50, height / 2 - 50, color(255, 0, 0));
  previewBox.hide();
}

function adjustInterfacePositions() {
  if (windowWidth >= 600) {   
    //escritorio
    tamanoXCanva=1000;
    tamanoYcanva=600;
    resizeCanvas(tamanoXCanva, tamanoYcanva);

    leaveNoteButton.position(tamanoXCanva/1.78, height +tamanoYcanva/16.66);

    colorButtons.forEach((button, i) => {
      button.position(tamanoXCanva/1.83 + i * 30, height - tamanoYcanva/3.75);
    });

    input.position(tamanoXCanva/1.75, height -  tamanoYcanva/5);

    button.position(tamanoXCanva/1.63, height -  tamanoYcanva/6.6);
  } else { // mobile
    leaveNoteButton.position(150, 700);

    colorButtons.forEach((button, i) => {
      button.position(100 + i * 30, 460);
    });

    input.position(80, 515);

    button.position(input.x + input.width + 10, input.y-10);
  }
}

function toggleInterface(show) {
  if (show) {
    input.show();
    button.show();
    colorButtons.forEach(button => button.show());
  } else {
    input.hide();
    button.hide();
    colorButtons.forEach(button => button.hide());
  }
}

function showPreview() {
  previewBox.show();
  previewBox.updateText(input.value());
  leaveNoteButton.hide();
}

function addMessage() {
  let inputText = input.value();
  let x = random(width - 50);
  let y = random(height - 50);
  let color = previewBox.color.toString('#rrggbb');

  let newMessage = new Message(inputText, x, y, color);
  document.getElementById('botonGenerarMuro').style.display = 'block';

  messages.push(newMessage);
  input.value('');
  if (isLeavingNote) {
    previewBox.hide();
    toggleInterface(false);
  }

  input.hide();
  button.hide();
  colorButtons.forEach(button => button.hide());
  leaveNoteButton.hide();

  document.getElementById('falseInputNotita').value = inputText;
  document.getElementById('falseInputPosX').value = x;
  document.getElementById('falseInputPosY').value = y;
  document.getElementById('falseInputColor').value = color;

  $.ajax({
    type: "POST",
    url: "guardarNota.php",
    data: $("#falseForm").serialize(),
    success: function(response) {
      console.log('Nota guardada:', response);
    }
  });
}

function mousePressed() {
  for (let message of messages) {
    if (message.isMouseInside()) {
      draggingNote = message;
      message.offsetX = mouseX - message.x;
      message.offsetY = mouseY - message.y;
      return;
    }
  }
}

function doubleClicked() {
  if (enlargedNote) {
    enlargedNote = null; // Cerrar la nota agrandada
  }
}

function mouseReleased() {
  if (draggingNote) {
    if (!enlargedNote) { // Evitar que se actualice la posición cuando se está haciendo doble clic para agrandar una nota
      $.ajax({
        type: "POST",
        url: "actualizarNota.php", // URL para actualizar la posición de la nota en el servidor
        data: {
          mensaje: draggingNote.text, // Utilizamos el mensaje como identificador
          posX: draggingNote.x,
          posY: draggingNote.y,
          color: draggingNote.color 
        },
        success: function(response) {
          console.log('Posición de la nota actualizada:', response);
        }
      });
    }
  }
  draggingNote = null;
}

function mouseClicked() {
  if (enlargedNote && enlargedNote.isMouseInside()) {
    enlargedNote = null; // Cerrar la nota agrandada al hacer clic sobre ella
  } else {
    for (let message of messages) {
      if (message.isMouseInside()) {
        enlargedNote = message; // Mostrar la nota agrandada
        return;
      }
    }
  }
}

class Message {
  constructor(text, x, y, color) {
    this.text = text;
    this.x = x;
    this.y = y;
    this.width = 100;
    this.height = 100;
    this.color = color;
    this.offsetX = 0;
    this.offsetY = 0;
  }

  display(enlarged = false) {
    textFont(customFont);
    if (enlarged) {
      fill(this.color);
      noStroke();
      rect(this.x, this.y, 200, 200); // Ajustar el tamaño de la nota agrandada
      fill(0);
      noStroke();
      textAlign(CENTER, CENTER);
      textSize(24);
      text(this.text, this.x + 100, this.y + 100); // Centrar el texto en la nota agrandada
    } else {
      fill(this.color);
      noStroke();
      rect(this.x, this.y, this.width, this.height);
      fill(0);
      noStroke();
      textAlign(CENTER, CENTER);
      textSize(12);
      text(this.text, this.x + this.width / 2, this.y + this.height / 2);
    }
  }

  isMouseInside() {
    return mouseX > this.x && mouseX < this.x + this.width && mouseY > this.y && mouseY < this.y + this.height;
  }

  updatePosition(x, y) {
    this.x = x;
    this.y = y;
  }
}

class PreviewBox {
  constructor(x, y, color) {
    this.x = x;
    this.y = y;
    this.width = 200;
    this.height = 200;
    this.color = color;
    this.visible = false;
    this.text = "";
  }

  show() {
    this.visible = true;
  }

  hide() {
    this.visible = false;
  }

  updateText(text) {
    this.text = text;
  }

  updateColor(newColor) {
    this.color = newColor;
  }

  display() {
    if (this.visible) {
      fill(0,100);
      rect(0,0,windowWidth,windowHeight);
      fill(this.color);
      noStroke();
      rect(this.x, this.y, this.width, this.height);
      fill(0);
      noStroke();
      textAlign(CENTER, CENTER);
      text(this.text, this.x + this.width / 2, this.y + this.height / 2);
    }
  }
}
