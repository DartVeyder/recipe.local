document.addEventListener('DOMContentLoaded', function () {
    // Функція для отримання рецептів
    function fetchRecipes() {
        fetch('/?url=api/recipes')  // Заміни на свій реальний API endpoint
            .then(response => response.json())
            .then(data => {
                const recipesContainer = document.getElementById('ca');
                const noRecipesMessage = document.getElementById('no-recipes-message');

                // Перевірка, чи є рецепти
                if (data.length === 0) {
                    noRecipesMessage.style.display = 'block';
                    return;
                } else {
                    noRecipesMessage.style.display = 'none';
                }

                // Очищаємо контейнер перед додаванням нових рецептів
                recipesContainer.innerHTML = '';

                // Додавання кожного рецепту до DOM
                data.forEach(recipe => {
                    const created_at = new Date(recipe.created_at).toLocaleDateString('uk-UA'); // Дата

                    // Створення HTML-контейнера для рецепта
                    const recipeCard = `
            <div class="col-12 col-sm-8 col-md-6 col-lg-4 mb-4">
              <div class="card">
                <img class="card-img-top" style="height: 16rem;" src="${recipe.image || 'assets/images/none_image.jpg'}" alt="${recipe.title}">
                <div class="card-img-overlay" style="right: auto;bottom: auto;width: 100%;display: flex;align-items: center;justify-content: space-between;">
                  <a href="?categories[]=${recipe.category_id}" class="btn btn-light btn-sm">${recipe.category_name}</a>
                  ${recipe.user_id === userId ? `
                    <div class="action">
                      <a href="?url=recipes/edit/${recipe.id}" class="fas fa-edit text-info"></a>
                      <a href="?url=recipes/delete/${recipe.id}" class="fas fa-trash-alt text-danger ml-3"></a>
                    </div>` : ''}
                </div>
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title">${recipe.title}</h4>
                    ${userId ? `
                      <a href="?url=wishlist/${recipe.is_in_wishlist ? 'delete' : 'create'}&user_id=${userId}&recipe_id=${recipe.id}">
                        <i class="${recipe.is_in_wishlist ? 'fas' : 'far'} fa-heart"></i>
                      </a>` : ''}
                  </div>
                  <p class="card-text">${recipe.description}</p>
                  <a href="?url=recipes/show/${recipe.id}" class="btn btn-info">Читати</a>
                </div>
                <div class="card-footer text-muted  bg-transparent border-top-0">
                  <div class="views d-flex  justify-content-between">
                    Дата створення: ${created_at}
                    <div class="stats">
                      <i class="far fa-eye"></i> ${recipe.views}
                    </div>
                  </div>
                  <div>
                    <p> Автор: ${recipe.user_name}</p>
                  </div>
                </div>
              </div>
            </div>
          `;

                    // Додаємо картку рецепту в контейнер
                    recipesContainer.innerHTML += recipeCard;
                });
            })
            .catch(error => console.error('Error fetching recipes:', error));
    }

    // Виклик функції для завантаження рецептів
    fetchRecipes();
});
