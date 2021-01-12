import Head from 'next/head';
import { Component, createRef } from 'react';

export default class App extends Component {
    todoDescription = createRef();

    static async getInitialProps () {
        const todosRequest = await fetch("http://localhost:8000/todo/list");
        const { todos } = await todosRequest.json();

        return { todos };
    }

    async removeTodo (id) {
        const todoRemovalRequest = await fetch(`http://localhost:8000/todo/remove/${id}`);
        const { result } = await todoRemovalRequest.json();

        if (!result) {
            return;
        }

        const { todos } = this.props;
        const todoIndex = todos.findIndex(todo => todo.id === id);

        if (todoIndex >= 0) {
            todos.splice(todoIndex, 1);
            this.saveLastUpdate();
        }
    }

    createTodo = async () => {
        const descriptionInput = this.todoDescription?.current;

        if (!descriptionInput) {
            return;
        }

        const body = new FormData;
        body.set("description", descriptionInput.value);
        const createTodoRequest = await fetch("http://localhost:8000/todo/create", { body, method: "post" });
        const { todo } = await createTodoRequest.json();

        if (todo) {
            this.props.todos.unshift(todo);
            descriptionInput.value = "";
            this.saveLastUpdate();
        }
    }

    saveLastUpdate () {
        this.setState({ lastUpdate: Date.now() });
    }

    render () {
        const { todos } = this.props;

        return (
            <div>
                <Head>
                    <title>SymfnextTodoList</title>
                </Head>

                <textarea cols="80" rows="10" ref={this.todoDescription} />
                <br />
                <button onClick={this.createTodo}>{"Create todo"}</button>

                {todos.map(({ id, description, createdAt }) => (
                    <div key={description + createdAt + id}>
                        <h2>{description}</h2>
                        <h4>{createdAt}</h4>
                        <button onClick={() => this.removeTodo(id)}>Remove</button>
                    </div>
                ))}
            </div>
        )
    }
}
