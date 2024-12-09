const div = document.getElementById("responsive-div");
const lightContainer = div;
div.addEventListener("mousemove", (e) => {
  // Get the bounding box of the div
  const rect = div.getBoundingClientRect();
  const x = e.clientX - rect.left; // Mouse X inside the div
  const y = e.clientY - rect.top; // Mouse Y inside the div

  // Create a new lighting effect element
  const light = document.createElement("div");
  light.classList.add(
    "absolute",
    "w-52",
    "h-52",
    "bg-indigo-200",
    "bg-opacity-10",
    "rounded-full",
    "blur-xl",
    "pointer-events-none"
  );

  // Append the new light element to the container first (before setting position)
  lightContainer.appendChild(light);

  // Once the light element is added to the DOM, adjust its position
  const lightWidth = light.offsetWidth; // Now the width/height is correctly calculated
  const lightHeight = light.offsetHeight;

  // Adjust light's position to center it on the mouse
  light.style.left = `${x - lightWidth / 2}px`;
  light.style.top = `${y - lightHeight / 2}px`;
  // Remove the lighting effect after 1 second (fade-out time)
  setTimeout(() => {
    light.style.transition = "opacity 1s ease-out"; // Fade out the effect
    light.style.opacity = 0;

    // After fade-out, remove it from the DOM
    setTimeout(() => {
      light.remove();
    }, 1000); // Remove after the fade-out transition
  }, 100); // Create a delay before fading out
});

div.addEventListener("mouseleave", () => {
  // Hide all lighting effects when the mouse leaves the div
  const allLights = lightContainer.querySelectorAll(".absolute");
  allLights.forEach((light) => {
    light.style.opacity = 0;
    setTimeout(() => light.remove(), 1000);
  });
});

div.addEventListener("mouseenter", () => {
  // Reset opacity of any existing lighting effect (if any)
  const allLights = lightContainer.querySelectorAll(".absolute");
  allLights.forEach((light) => {
    light.style.opacity = 1;
  });
});
