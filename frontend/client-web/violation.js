document.addEventListener('DOMContentLoaded', function () {
    // Initially show the home content
    changeContent('PhilLaw', document.querySelector('.navbar a'));
});

function changeContent(page, clickedNavLink) {
    // Hide all content divs
    const contentDivs = document.querySelectorAll('.content');
    contentDivs.forEach(div => {
        div.style.display = 'none';
    });

    // Remove 'active' class from all navbar links
    const navLinks = document.querySelectorAll('.navbar a');
    navLinks.forEach(link => {
        link.classList.remove('active');
    });

    // Show the selected content
    const selectedContent = document.getElementById(`${page}-content`);
    selectedContent.style.display = 'block';

    // Add 'active' class to the clicked navbar link
    clickedNavLink.classList.add('active');
}


// Function to open a specific form modal
function openForm(formId) {
    var modal = document.getElementById(formId);
    modal.style.display = "block";
  }
  
  // Function to close a specific form modal
  function closeForm(formId) {
    var modal = document.getElementById(formId);
    modal.style.display = "none";
  }
  
  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    });
  }
  