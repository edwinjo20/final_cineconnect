import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  imports: [CommonModule, FormsModule],
  styleUrls: ['./login.component.css']
})
export class LoginComponent {
  email: string = '';
  password: string = '';

  constructor(private http: HttpClient, private router: Router) {}

  login(): void {
    this.http
      .post('http://localhost:8000/api/login_check', {
        email: this.email,
        password: this.password,
      })
      .subscribe(
        (response: any) => {
          if (response.token) {
            localStorage.setItem('token', response.token);
            localStorage.setItem('user', JSON.stringify({ email: this.email }));
            alert('Login Successful!');
            
            // ✅ Redirect to the root path (/) after login
            this.router.navigate(['/']);
          }
        },
        (error) => {
          alert('Login Failed!');
        }
      );
  }
}
