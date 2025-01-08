import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-film-list',
  imports: [CommonModule, FormsModule],
  templateUrl: './film-list.component.html',
  styleUrls: ['./film-list.component.css'],
})
export class FilmListComponent implements OnInit {
  films: any[] = [];
  reviews: { [filmId: number]: { content: string; rating: number } } = {};
  isLoggedIn: boolean = false;

  constructor(private http: HttpClient) {}

  ngOnInit(): void {
    this.checkLoginStatus();
    this.fetchFilms();
  }

  checkLoginStatus(): void {
    const storedUser = localStorage.getItem('user');
    this.isLoggedIn = !!storedUser;
  }

  fetchFilms(): void {
    this.http.get('http://localhost:8000/api/films').subscribe(
      (response: any) => {
        this.films = response.map((film: any) => {
          // ✅ Use the imagePath directly from the backend response
          film.imagePath = `http://localhost:4200/${film.imagePath}`;
          return film;
        });
  
        this.films.forEach((film) => {
          this.reviews[film.id] = { content: '', rating: 0 };
        });
      },
      (error) => {
        alert('Failed to fetch films. Please check your backend connection.');
      }
    );
  }
  

  submitReview(filmId: number): void {
    if (!this.isLoggedIn) {
      alert('You need to log in to write a review.');
      return;
    }

    const reviewContent = this.reviews[filmId].content;
    const reviewRating = this.reviews[filmId].rating;

    if (!reviewContent || reviewRating < 1 || reviewRating > 5) {
      alert('Please fill in all fields correctly. Rating must be between 1 and 5.');
      return;
    }

    const reviewData = {
      userId: 1, // Replace with the actual logged-in user's ID
      filmId: filmId,
      content: reviewContent,
      ratingGiven: reviewRating,
    };

    this.http.post('http://localhost:8000/api/reviews', reviewData).subscribe(
      (response) => {
        alert('Review submitted successfully!');
        this.reviews[filmId] = { content: '', rating: 0 };
        this.fetchFilms();
      },
      (error) => {
        alert('Error submitting review. Please try again.');
      }
    );
  }

  logout(): void {
    localStorage.removeItem('user');
    this.isLoggedIn = false;
    location.reload();
  }
}