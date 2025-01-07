import { Component } from '@angular/core';
import { FilmService } from '../../services/film.service';
import { FormsModule } from '@angular/forms';


@Component({
  selector: 'app-admin-film',
  imports: [FormsModule],
  templateUrl: './admin-film.component.html',
  styleUrls: ['./admin-film.component.css']
})
export class AdminFilmComponent {
  filmData = {
    title: '',
    description: '',
    releaseDate: '',
    genre: '',
    imagePath: ''
  };

  constructor(private filmService: FilmService) {}

  addFilm() {
    this.filmService.addFilm(this.filmData).subscribe({
      next: (response) => {
        alert('Film added successfully!');
        console.log(response);
      },
      error: (error) => {
        alert('Failed to add film');
        console.error(error);
      }
    });
  }
}
