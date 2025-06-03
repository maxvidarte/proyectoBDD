// const enlaceLogin=document.createElement('A');

// enlaceLogin.href='login.php';

// enlaceLogin.textContent='Iniciar Sesión';

// enlaceLogin.classList.add('navegacion__enlace');

// const insertar=document.querySelector('.navegacion');

// insertar.appendChild(enlaceLogin);


// fetch('session.php')
//   .then(res => res.json())
//   .then(data => {
//     const enlaceLogin = document.createElement('A');
//     enlaceLogin.classList.add('navegacion__enlace');

//     if (data.logueado) {
//       enlaceLogin.textContent = `👤 Hi, ${data.nombres}`;
//       enlaceLogin.href = 'main.html'; 
//     } else {
//       enlaceLogin.textContent = 'Iniciar Sesión';
//       enlaceLogin.href = 'login.html';
//     }

    
//     document.querySelector('.navegacion').appendChild(enlaceLogin);
//   });

fetch('/php/session.php')
  .then(res => res.json())
  .then(data => {
    const contenedor = document.querySelector('.navegacion');

    const enlace = document.createElement('a');
    enlace.classList.add('navegacion__enlace'); // o .usuario-logueado si usas un estilo distinto

    if (data.logueado) {
      enlace.href = '/php/logout.php';
      
      enlace.textContent = ` Hi, ${data.nombres}`;
      enlace.classList.add('usuario-logueado');

       // Evento para cambiar el texto al hacer hover
      const textoOriginal = enlace.textContent;
      enlace.addEventListener('mouseenter', () => {
        enlace.textContent = 'Cerrar Sesión';
      });
      enlace.addEventListener('mouseleave', () => {
        enlace.textContent = textoOriginal;
      });

    } else {
      enlace.href = '/login/login.html';
      enlace.textContent = 'Iniciar Sesión';
    }

    contenedor.prepend(enlace);
    
    });

 